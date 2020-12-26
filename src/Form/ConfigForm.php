<?php declare(strict_types=1);

namespace IiifViewers\Form;

use Laminas\EventManager\Event;
use Laminas\EventManager\EventManagerAwareTrait;
use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;

class ConfigForm extends Form implements TranslatorAwareInterface
{
    use EventManagerAwareTrait;
    use TranslatorAwareTrait;

    public function init(): void
    {
        $this
            ->add([
                'name' => 'iiifviewers_mirador',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'Mirador', // @translate
                    'info' => "URL of Mirador.", //$this->translate("IIIF Manifest"),  // URL of Mirador.
                ],
                'attributes' => [
                    'id' => 'iiifviewers_mirador',
                    'data-placeholder' => 'http://da.dl.itc.u-tokyo.ac.jp/mirador/?manifest=', // @translate
                ],
            ])

            ->add([
                'name' => 'iiifviewers_universal_viewer',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'Universal Viewer', // @translate
                    'info' => "URL of Universal Viewer",  // @translate
                ],
                'attributes' => [
                    'id' => 'iiifviewers_universal_viewer',
                    'data-placeholder' => 'http://universalviewer.io/examples/uv/uv.html#?manifest=', // @translate
                ],
            ])

            ->add([
                'name' => 'iiifviewers_curation_viewer',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'IIIF Curation Viewer', // @translate
                    'info' => "URL of IIIF Curation Viewer",  // @translate
                ],
                'attributes' => [
                    'id' => 'iiifviewers_curation_viewer',
                    'data-placeholder' => 'http://codh.rois.ac.jp/software/iiif-curation-viewer/demo/?manifest=', // @translate
                ],
            ])

            ->add([
                'name' => 'iiifviewers_tify',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'TIFY', // @translate
                    'info' => "URL of TIFY",  // @translate
                ],
                'attributes' => [
                    'id' => 'iiifviewers_tify',
                    'data-placeholder' => 'http://demo.tify.rocks/demo.html?manifest=', // @translate
                ],
            ])
        ;

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
