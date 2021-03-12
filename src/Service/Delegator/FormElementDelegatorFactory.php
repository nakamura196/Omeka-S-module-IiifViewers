<?php
namespace IiifViewers\Service\Delegator;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;

/**
 * Map custom element types to the view helpers that render them.
 * ElementとViewのマッピング
 */
class FormElementDelegatorFactory implements DelegatorFactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $name,
        callable $callback,
        array $options = null
    ) {
        $formElement = $callback();
        $formElement->addClass('IiifViewers\Form\Element\Icon', 'formIcon');
        $formElement->addClass('IiifViewers\Form\Element\IconThumbnail', 'formIconThumbnail');
        return $formElement;
    }
}
