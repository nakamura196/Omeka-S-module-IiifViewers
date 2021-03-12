<?php declare(strict_types=1);

namespace IiifViewers\Service\ViewHelper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use IiifViewers\View\Helper\IiifViewers;

/**
 * Service factory for the IiifViewers view helper.
 */
class IiifViewersFactory implements FactoryInterface
{
    /**
     * Create and return the IiifViewers view helper
     *
     * @return IiifViewers
     */
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $currentTheme = $services->get('Omeka\Site\ThemeManager')
            ->getCurrentTheme();
        return new IiifViewers($currentTheme);
    }
}
