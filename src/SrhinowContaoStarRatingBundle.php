<?php

/**
 * @copyright  Sven Rhinow 2019
 * @author     sr-tag Sven Rhinow Webentwicklung <http://www.sr-tag.de>
 * @package    contao-starrating-bundle
 * @license   LGPL-3.0-or-later
 * @filesource
 */

namespace Srhinow\ContaoStarRatingBundle;

use Srhinow\ContaoStarRatingBundle\DependencyInjection\SrhinowContaoStarRatingBundleExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SrhinowContaoStarRatingBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new SrhinowContaoStarRatingBundleExtension();
    }
}
