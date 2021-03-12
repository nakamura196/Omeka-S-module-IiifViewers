<?php
namespace IiifViewers\Service\Controller\Admin;

use Interop\Container\ContainerInterface;
use IiifViewers\Controller\Admin\IndexController;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * IndexControllerFactory
 * アイコン設定コントローラ用
 */
class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        return new IndexController($serviceLocator);
    }
}
