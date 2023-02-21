<?php declare(strict_types=1);
namespace IiifViewers\Service\Form;

use IiifViewers\Form\ConfigForm;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * ConfigFormFactory
 * 設定フォーム用
 */
class ConfigFormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $form = new ConfigForm(null, $options ?? []);
        $form
            ->setTranslator($services->get('MvcTranslator'))
            ->setEventManager($services->get('EventManager'));
        return $form;
    }
}