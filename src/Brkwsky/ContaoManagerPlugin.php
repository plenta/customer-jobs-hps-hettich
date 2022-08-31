<?php

declare(strict_types=1);

/**
 * @package       Customer HPS Hettich
 * @copyright     Copyright (c) 2022, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */


use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouteCollection;

class ContaoManagerPlugin implements BundlePluginInterface, RoutingPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create('Brkwsky\JobsGeo\BrkwskyJobsGeoBundle')
                ->setLoadAfter([
                    'Contao\CoreBundle\ContaoCoreBundle',
                ]),

            BundleConfig::create('Brkwsky\Jobs\BrkwskyJobsBundle')
                ->setLoadAfter([
                    'Contao\CoreBundle\ContaoCoreBundle',
                    'Brkwsky\JobsGeo\BrkwskyJobsGeoBundle',
                ]),

            BundleConfig::create('Brkwsky\JobsAttributes\BrkwskyJobsAttributesBundle')
                ->setLoadAfter([
                    'Contao\CoreBundle\ContaoCoreBundle',
                    'Brkwsky\Jobs\BrkwskyJobsBundle',
                ]),

            BundleConfig::create('Brkwsky\JobsStructuredData\BrkwskyJobsStructuredDataBundle')
                ->setLoadAfter([
                    'Contao\CoreBundle\ContaoCoreBundle',
                    'Brkwsky\Jobs\BrkwskyJobsBundle',
                ]),

            BundleConfig::create('Brkwsky\JobsImport\BrkwskyJobsImportBundle')
                ->setLoadAfter([
                    'Contao\CoreBundle\ContaoCoreBundle',
                    'Brkwsky\Jobs\BrkwskyJobsBundle',
                ]),

            BundleConfig::create('Brkwsky\Customer\BrkwskyCustomerBundle')
                ->setLoadAfter([
                    'Contao\CoreBundle\ContaoCoreBundle',
                    'Brkwsky\Jobs\BrkwskyJobsBundle',
                    'Plenta\JobsProductHelper\PlentaJobsProductHelperBundle',
                    'Plenta\Payment\PlentaPaymentBundle',
                    'Plenta\Products\PlentaProductsBundle',
                    'Brkwsky\JobsImport\BrkwskyJobsImportBundle',
                ]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        $collections = [];

        $files = [
            '@BrkwskyJobsBundle/Resources/config/routing.yml',
            '@BrkwskyJobsImportBundle/Resources/config/routing.yml',
            '@BrkwskyCustomerBundle/Resources/config/routing.yml',
        ];

        foreach ($files as $file) {
            /** @var RouteCollection $collection */
            $collection = $resolver->resolve($file)->load($file);

            $collections[] = $collection;
        }

        $collection = array_reduce(
            $collections,
            function (RouteCollection $carry, RouteCollection $item) {
                $carry->addCollection($item);

                return $carry;
            },
            new RouteCollection()
        );

        /*
        // Only for one file
        $file = '@BrkwskyJobsBundle/Resources/config/routing.yml';
        return $resolver->resolve($file)->load($file);
        */

        return $collection;
    }
}
