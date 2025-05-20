<?php

namespace Ramyakant\ProductManagementBundle\Service;

use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Service;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductManagementService
{
    /**
     * Creates or updates a Product object.
     *
     * @param string $sku
     * @param string|null $name
     * @param float $price
     * @return array
     * @throws BadRequestHttpException
     */
    public function createOrUpdateProduct(string $sku, ?string $name, float $price): array
    {
        // Validate inputs
        if (empty($sku)) {
            throw new BadRequestHttpException('SKU is required.');
        }
        if ($price < 0) {
            throw new BadRequestHttpException('Price cannot be negative.');
        }

        // Check if product exists
        $product = Product::getBySku($sku, ['limit' => 1]);
        $operation = 'Created';

        if ($product instanceof Product) {
            // Update existing product
            $product->setName($name);
            $product->setPrice($price);
             $operation = 'Updated';
        } else {
            // Create new product
            $product = new Product();
            $product->setParent(Service::createFolderByPath('/Products')); // Set parent folder
            $product->setKey($sku); // Use SKU as object key
            $product->setSku($sku);
            $product->setName($name);
            $product->setPrice($price);
            $product->setPublished(true);
        }

        // Save the product
        $product->save();

        return [ 'product' => $product, 'operation' => $operation ];
    }
}