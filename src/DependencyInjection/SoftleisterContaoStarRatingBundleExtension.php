<?php

/**
 * @copyright 2018 Felix Pfeiffer : Neue Medien
 * @author    Felix Pfeiffer : Neue Medien
 * @author    Sven Rhinow
 * @package   contao-starrating-bundle
 * @license   LGPL-3.0-or-later
 *
 */

namespace Softleister\ContaoStarRatingBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Adds the bundle services to the container.
 *
 * @author Sven Rhinow <https://gitlab.com/srhinow>
 */
class SoftleisterContaoStarRatingBundleExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yml');
        $loader->load('listener.yml');

    }
}
