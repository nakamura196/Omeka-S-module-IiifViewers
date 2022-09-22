<?php
namespace IiifViewers\Form;

use Laminas\Form\Form;
use IiifViewers\Form\Element\Icon;

/**
 * IndexForm
 * アイコン設定フォーム
 */
class IndexForm extends Form
{
    protected $mappingClasses;

    public function init()
    {
        // ロゴ
        $this->add([
            'name' => 'logo',
            'type' => Icon::class,
            'options' => [
                'label' => 'LOGO', // @translate
                'info' => 'Choose IiiF Viewers Module Logo', // @translate
            ],
        ]);
        // Mirador
        $this->add([
            'name' => 'iiifviewers_mirador_icon',
            'type' => Icon::class,
            'options' => [
                'label' => 'Mirador', // @translate
                'info' => 'Choose Mirador Icon', // @translate
            ],
        ]);
        // Universal Viewer
        $this->add([
            'name' => 'iiifviewers_universal_viewer_icon',
            'type' => Icon::class,
            'options' => [
            'label' => 'Universal Viewer', // @translate
                'info' => 'Choose Universal Viewer Icon', // @translate
            ],
        ]);
        // IIIF Curation Viewer
        $this->add([
            'name' => 'iiifviewers_curation_viewer_icon',
            'type' => Icon::class,
            'options' => [
                'label' => 'IIIF Curation Viewer', // @translate
                'info' => 'Choose IIIF Curation Viewer Icon', // @translate
            ],
        ]);
        /*
        // Tiffy
        $this->add([
            'name' => 'iiifviewers_tify_icon',
            'type' => Icon::class,
            'options' => [
                'label' => 'Tify', // @translate
                'info' => 'Choose Tify Icon', // @translate
            ],
        ]);
        */
        // Image Annotator
        $this->add([
            'name' => 'iiifviewers_ia_icon',
            'type' => Icon::class,
            'options' => [
                'label' => 'Image Annotator', // @translate
                'info' => 'Choose Image Annotator Icon', // @translate
            ],
        ]);
        // 以下各ビューワーのURLをHiddenで設定する
        $this->add([
            'name' => 'iiifviewers_mirador',
            'type' => \Laminas\Form\Element\Hidden::class,
        ]);
        $this->add([
            'name' => 'iiifviewers_universal_viewer',
            'type' => \Laminas\Form\Element\Hidden::class,
        ]);
        $this->add([
            'name' => 'iiifviewers_curation_viewer',
            'type' => \Laminas\Form\Element\Hidden::class,
        ]);
        /*
        $this->add([
            'name' => 'iiifviewers_tify',
            'type' => \Laminas\Form\Element\Hidden::class,
        ]);
        */
        $this->add([
            'name' => 'iiifviewers_ia',
            'type' => \Laminas\Form\Element\Hidden::class,
        ]);
    }
}
