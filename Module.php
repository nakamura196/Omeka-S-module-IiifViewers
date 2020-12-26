<?php declare(strict_types=1);

namespace IiifViewers;

use Omeka\Module\AbstractModule;
use IiifViewers\Form\ConfigForm;
use Laminas\Mvc\Controller\AbstractController;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\View\Renderer\PhpRenderer;

class Module extends AbstractModule
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    protected function postInstall(): void
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        $settings->set('iiifviewers', $settings->get('iiifviewers'));
    }

    public function getConfigForm(PhpRenderer $renderer)
    {
        $translate = $renderer->plugin('translate');

        $services = $this->getServiceLocator();
        $config = $services->get('Config');
        $settings = $services->get('Omeka\Settings');
        $form = $services->get('FormElementManager')->get(ConfigForm::class);

        $data = $settings->get('iiifviewers', ['']);

        $form->init();
        $form->setData($data); //$params
        $html = $renderer->formCollection($form);

        return '<p>'
            . $translate('Please set urls of viewers.') // @translate
            . '</p>'
            . $html; //parent::getConfigForm($renderer);//$html;
    }

    public function handleConfigForm(AbstractController $controller)
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        $form = $services->get('FormElementManager')->get(ConfigForm::class);

        $params = $controller->getRequest()->getPost();

        $form->init();
        $form->setData($params);

        //以下の違いがわからない
        /*
        if (!$form->isValid()) {
            $controller->messenger()->addErrors($form->getMessages());
            return false;
        }
        */

        $form->isValid();
        $params = $form->getData();

        $settings->set('iiifviewers', $params);
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager): void
    {
        $sharedEventManager->attach(
            'Omeka\Controller\Site\Item',
            'view.show.after',
            [$this, 'handleViewShowAfterItem']
        );
    }

    public function handleViewShowAfterItem(Event $event): void
    {
        $view = $event->getTarget();
        echo $view->IiifViewers($view->item);
    }
}
