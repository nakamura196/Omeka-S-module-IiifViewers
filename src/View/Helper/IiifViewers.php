<?php declare(strict_types=1);

namespace IiifViewers\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;
use Omeka\Site\Theme\Theme;

class IiifViewers extends AbstractHelper
{
    /**
     * @var Theme The current theme, if any
     */
    protected $currentTheme;

    /**
     * Construct the helper.
     *
     * @param Theme|null $currentTheme
     */
    public function __construct(Theme $currentTheme = null)
    {
        $this->currentTheme = $currentTheme;
    }

    /**
     * Get the Universal Viewer for the provided resource.
     *
     * Proxies to {@link render()}.
     *
     * @param AbstractResourceEntityRepresentation|AbstractResourceEntityRepresentation[] $resource
     * @param array $options
     * @return string Html string corresponding to the viewer.
     */
    public function __invoke($resource, $options = []): string
    {
        if (empty($resource)) {
            return '';
        }

        $view = $this->getView();

        // If the manifest is not provided in metadata, point to the manifest
        // created from Omeka files only when the Iiif Server is installed.
        $iiifServerIsActive = $view->getHelperPluginManager()->has('iiifUrl');

        $isCollection = is_array($resource);

        // Prepare the url of the manifest for a dynamic collection.
        if ($isCollection) {
            if (!$iiifServerIsActive) {
                return '';
            }
            $urlManifest = $view->iiifUrl($resource);
            return $this->render($urlManifest, $options, 'multiple');
        }

        // Prepare the url for the manifest of a record after additional checks.
        $resourceName = $resource->resourceName();
        if (!in_array($resourceName, ['items', 'item_sets'])) {
            return '';
        }

        // Determine the url of the manifest from a field in the metadata.
        $externalManifest = $view->iiifManifestExternal($resource);
        if ($externalManifest) {
            return $this->render($externalManifest, $options, $resourceName, true);
        }

        // If the manifest is not provided in metadata, point to the manifest
        // created from Omeka files if the module Iiif Server is enabled.
        if (!$iiifServerIsActive) {
            return '';
        }

        // Some specific checks.
        switch ($resourceName) {
            case 'items':
                // Currently, an item without files is unprocessable.
                if (count($resource->media()) == 0) {
                    // return $view->translate('This item has no files and is not displayable.');
                    return '';
                }
                break;
            case 'item_sets':
                if ($resource->itemCount() == 0) {
                    // return $view->translate('This collection has no item and is not displayable.');
                    return '';
                }
                break;
        }

        $urlManifest = $view->iiifUrl($resource);
        return $this->render($urlManifest, $options, $resourceName);
    }

    /**
     * Render a universal viewer for a url, according to options.
     *
     * @param string $urlManifest
     * @param array $options
     * @param string $resourceName
     * @return string Html code.
     */
    protected function render($urlManifest, array $options = [], $resourceName = null)
    {
        //表示用
        $iiifViewersSetting = "iiifViewersSetting";

        // static $id = 0;

        $view = $this->view;

        $assetUrl = $view->plugin('assetUrl');
        
        //データのロード
        $settings = $view->setting($iiifViewersSetting);

        $config = [
            'asset' => $assetUrl('', 'IiifViewers', false, false),
            'iiifResourceUri' => $urlManifest,
            'setting' => $settings,
        ];

        return $view->partial('common/helper/iiif-viewers', [
            'config' => $config,
        ]);
    }

    /**
     * Get an asset path for a site from theme or module (fallback).
     *
     * @param string $path
     * @param string $module
     * @return string|null
     */
    protected function assetPath($path, $module = null)
    {
        // Check the path in the theme.
        if ($this->currentTheme) {
            $filepath = OMEKA_PATH . '/themes/' . $this->currentTheme->getId() . '/asset/' . $path;
            if (file_exists($filepath)) {
                return $this->view->assetUrl($path, null, false, false);
            }
        }

        // As fallback, get the path in the module (the file must exist).
        if ($module) {
            $assetPath = $this->view->assetUrl($path, $module, false, false);
            return $assetPath;
        }
    }
}
