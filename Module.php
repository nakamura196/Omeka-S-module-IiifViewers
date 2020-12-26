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

    public function getConfigForm(PhpRenderer $renderer)
    {
        $translate = $renderer->plugin('translate');

        $services = $this->getServiceLocator();
        $config = $services->get('Config');
        $settings = $services->get('Omeka\Settings');
        $form = $services->get('FormElementManager')->get(ConfigForm::class);

        $data = $settings->get('iiifviewers', ['']);

        $form->init();
        $form->setData($data);
        $html = $renderer->formCollection($form);

        return '<p>'
            . $translate('Please set urls of viewers.') // @translate
            . '</p>'
            . $html;
    }

    public function handleConfigForm(AbstractController $controller)
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        $form = $services->get('FormElementManager')->get(ConfigForm::class);

        $params = $controller->getRequest()->getPost();

        $form->init();
        $form->setData($params);

        if (!$form->isValid()) {
            $controller->messenger()->addErrors($form->getMessages());
            return false;
        }

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
