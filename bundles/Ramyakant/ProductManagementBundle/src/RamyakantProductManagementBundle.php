<?php

namespace Ramyakant\ProductManagementBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\PimcoreBundleAdminClassicInterface;
use Pimcore\Extension\Bundle\Traits\BundleAdminClassicTrait;

class RamyakantProductManagementBundle extends AbstractPimcoreBundle implements PimcoreBundleAdminClassicInterface
{
    use BundleAdminClassicTrait;

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function getJsPaths(): array
    {
        return [
            '/bundles/ramyakantproductmanagement/js/pimcore/startup.js',
            '/bundles/ramyakantproductmanagement/js/pimcore/product_form.js'
        ];
    }

    public function getCssPaths(): array
    {
        return [
            '/bundles/ramyakantproductmanagement/css/style.css'
        ];
    }

}