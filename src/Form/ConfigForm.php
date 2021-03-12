<?php declare(strict_types=1);

namespace IiifViewers\Form;

use Laminas\EventManager\Event;
use Laminas\EventManager\EventManagerAwareTrait;
use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\Form\Fieldset;
use IiifViewers\Form\Element\IconThumbnail;

/**
 * ConfigForm
 * 設定フォーム
 */
class ConfigForm extends Form implements TranslatorAwareInterface
{
    use EventManagerAwareTrait;
    use TranslatorAwareTrait;

    public function init(): void
    {
        $this
        // ロゴ
        ->add([
            'name' => 'logo',
            'type' => IconThumbnail::class,
            'options' => [
                'label' => 'LOGO', // @translate
            ],
        ])
        // Mirador
            ->add([
                'name' => 'mirador_title',
                'type' => Fieldset::class,
                'options' => [
                    'label' => 'Mirador', // @translate
                ],
                'attributes' => [
                    'id' => 'mirador_title',
                    'style' => 'margin:0;padding:0;'
                ],
            ])
            // URL
            ->add([
                'name' => 'iiifviewers_mirador',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'URL', // @translate
                    'info' => "URL of Mirador.", //$this->translate("IIIF Manifest"),  // URL of Mirador.
                ],
                'attributes' => [
                    'id' => 'iiifviewers_mirador',
                    'data-placeholder' => 'http://da.dl.itc.u-tokyo.ac.jp/mirador/?manifest=', // @translate
                ],
            ])
            // アイコン
            ->add([
                'name' => 'iiifviewers_mirador_icon',
                'type' => IconThumbnail::class,
                'options' => [
                    'label' => 'ICON', // @translate
                ],
               
            ])
            // Universal Viewer
            ->add([
                'name' => 'universal_viewer_title',
                'type' => Fieldset::class,
                'options' => [
                    'label' => 'Universal Viewer', // @translate
                ],
                'attributes' => [
                    'id' => 'universal_viewer_title',
                    'style' => 'margin:0;padding:0;'
                ],
            ])
            // URL
            ->add([
                'name' => 'iiifviewers_universal_viewer',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'URL', // @translate
                    'info' => "URL of Universal Viewer",  // @translate
                ],
                'attributes' => [
                    'id' => 'iiifviewers_universal_viewer',
                    'data-placeholder' => 'http://universalviewer.io/examples/uv/uv.html#?manifest=', // @translate
                ],
            ])
            // アイコン
            ->add([
                'name' => 'iiifviewers_universal_viewer_icon',
                'type' => IconThumbnail::class,
                'options' => [
                    'label' => 'ICON', // @translate
                ],
            ])
            // IIIF Cufation Viewer
            ->add([
                'name' => 'curation_viewer_title',
                'type' => Fieldset::class,
                'options' => [
                    'label' => 'IIIF Curation Viewer', // @translate
                ],
                'attributes' => [
                    'id' => 'curation_viewer_title',
                    'style' => 'margin:0;padding:0;'
                ],
            ])
            // URL
            ->add([
                'name' => 'iiifviewers_curation_viewer',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'URL', // @translate
                    'info' => "URL of IIIF Curation Viewer",  // @translate
                ],
                'attributes' => [
                    'id' => 'iiifviewers_curation_viewer',
                    'data-placeholder' => 'http://codh.rois.ac.jp/software/iiif-curation-viewer/demo/?manifest=', // @translate
                ],
            ])
            // アイコン
            ->add([
                'name' => 'iiifviewers_curation_viewer_icon',
                'type' => IconThumbnail::class,
                'options' => [
                    'label' => 'ICON', // @translate
                ],
            ])
            // TIFFY
            ->add([
                'name' => 'tify_title',
                'type' => Fieldset::class,
                'options' => [
                    'label' => 'TIFY', // @translate
                ],
                'attributes' => [
                    'id' => 'tify_title',
                    'style' => 'margin:0;padding:0;'
                ],
            ])
            // URL
            ->add([
                'name' => 'iiifviewers_tify',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'URL', // @translate
                    'info' => "URL of TIFY",  // @translate
                ],
                'attributes' => [
                    'id' => 'iiifviewers_tify',
                    'data-placeholder' => 'http://demo.tify.rocks/demo.html?manifest=', // @translate
                ],
            ])
            // アイコン
            ->add([
                'name' => 'iiifviewers_tify_icon',
                'type' => IconThumbnail::class,
                'options' => [
                    'label' => 'ICON', // @translate
                ],
            ])
        ;
        // 以下そのまま
        $addEvent = new Event('form.add_elements', $this);
        $this->getEventManager()->triggerEvent($addEvent);

        $inputFilter = $this->getInputFilter();
        $inputFilter
            ->add([
                'name' => 'iiifviewers_mirador',
                'required' => false,
            ])
            ->add([
                'name' => 'iiifviewers_universal_viewer',
                'required' => false,
            ])
            ->add([
                'name' => 'iiifviewers_curation_viewer',
                'required' => false,
            ])
            ->add([
                'name' => 'iiifviewers_tify',
                'required' => false,
            ])
        ;

        $filterEvent = new Event('form.add_input_filters', $this, ['inputFilter' => $inputFilter]);
        $this->getEventManager()->triggerEvent($filterEvent);
    }

    protected function translate($args)
    {
        $translator = $this->getTranslator();
        return $translator->translate($args);
    }
}
