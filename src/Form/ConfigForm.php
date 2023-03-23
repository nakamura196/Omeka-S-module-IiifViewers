<?php declare(strict_types=1);

// /admin/module/configure?id=IiifViewers

namespace IiifViewers\Form;
// 追加
use IiifViewers\Form\Element\Icon;

use Laminas\EventManager\Event;
use Laminas\EventManager\EventManagerAwareTrait;
use Laminas\Form\Element;
// use Laminas\Form\Element\Asset;
use Laminas\Form\Form;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\Form\Fieldset;
// use IiifViewers\Form\Element\IconThumbnail;

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

        // 以下そのまま
        $addEvent = new Event('form.add_elements', $this);
        $this->getEventManager()->triggerEvent($addEvent);

        $inputFilter = $this->getInputFilter();

        // manifest

        $this
        ->add([
            'name' => 'manifest_title',
            'type' => Fieldset::class,
            'options' => [
                'label' => 'Manifest', // @translate
            ],
            'attributes' => [
                'id' => 'manifest_title',
                'style' => 'margin:0;padding:0;',
            ],
        ])
        // ロゴ
        ->add([
            'name' => 'manifest_icon',
            'type' => Icon::class,
            'options' => [
                'label' => 'ICON', // @translate
            ],
        ]);

        for($i = 0; $i < 5; $i++){
            $index = $i + 1;

            // タイトル
            $this->add([
                'name' => 'title_'.$index,
                'type' => Fieldset::class,
                'options' => [
                    'label' => "Viewer ".$index, //$config["label_".$index], // @translate
                ],
                'attributes' => [
                    'name' => 'title_'.$index,
                    'style' => 'margin:0;padding:0;',
                ],
            ]);

            //ラベル
            $this->add([
                'name' => "label_".$index,
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'Label', // @translate
                ],
                'attributes' => [
                    'id' => "label_".$index
                ],
            ]);

            //URL
            $this->add([
                'name' => "url_".$index,
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'URL', // @translate
                ],
                'attributes' => [
                    'id' => "url_".$index
                ],
            ]);

            //ICON
            $this->add([
                'name' => "icon_".$index,
                'type' => Icon::class,
                'options' => [
                    'label' => "ICON", // @translate
                ],
            ]);
        };

        $this
        ->add([
            'name' => 'viewer_title',
            'type' => Fieldset::class,
            'options' => [
                'label' => 'Settings', // @translate
            ],
            'attributes' => [
                'id' => 'viewer_title',
                'style' => 'margin:0;padding:0;',
            ],
        ])
        // ロゴ
        ->add([
            'name' => 'viewer_value',
            'type' => Element\Radio::class,
            'options' => [
                'label' => 'Tool', // @translate
                'value_options' => [
                    'uv' => 'Universal Viewer', // @translate
                    'mirador' => 'Mirador', // @translate
                ],
            ]            
        ])
        ->add([
            'name' => 'viewer_lang',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Language',
            ]       
        ])
        ->add([
            'name' => 'viewer_help',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Help URL',
            ]            
        ])
        ->add([
            'name' => 'viewer_help_text',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Text',
            ]            
        ]);

        $filterEvent = new Event('form.add_input_filters', $this, ['inputFilter' => $inputFilter]);
        $this->getEventManager()->triggerEvent($filterEvent);
    }

    protected function translate($args)
    {
        $translator = $this->getTranslator();
        return $translator->translate($args);
    }
}