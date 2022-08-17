<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2022, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\Customer\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Plenta\Customer\PlentaCustomerBundle;

/**
 * Class ContaoManagerPlugin.
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(PlentaCustomerBundle::class)
                ->setLoadAfter([
                    ContaoCoreBundle::class,
                ]),
        ];
    }
}
