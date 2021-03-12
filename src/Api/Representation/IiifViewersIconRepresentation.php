<?php
namespace IiifViewers\Api\Representation;

use Omeka\Api\Representation\AbstractEntityRepresentation;

/**
 * IiifViewersIconRepresentation
 * アイコンデータ用Representation
 */
class IiifViewersIconRepresentation extends AbstractEntityRepresentation
{
    public function getControllerName()
    {
        return 'iiif_viewers_icon';
    }

    public function getJsonLdType()
    {
        return 'o:IiifViewrsIcon';
    }

    public function getJsonLd()
    {
        return [
            'o:id' => $this->id(),
            'o:name' => $this->name(),
            'o:filename' => $this->filename(),
            'o:asset_url' => $this->assetUrl(),
        ];
    }

    public function name()
    {
        return $this->resource->getName();
    }

    public function filename()
    {
        return $this->resource->getFilename();
    }

    public function assetUrl()
    {
        return $this->getFileUrl('asset', $this->filename());
    }
}
