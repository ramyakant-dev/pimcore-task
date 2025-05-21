<?php

declare(strict_types=1);

namespace Ramyakant\ProductManagementBundle\Tests\Service;

use Pimcore\Model\DataObject\Product;
use Pimcore\Test\KernelTestCase;
use Ramyakant\ProductManagementBundle\Service\ProductManagementService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductManagementServiceTest extends KernelTestCase
{
    private ProductManagementService $service;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        // Clear test products
        $product = Product::getBySku('PROD010', ['limit' => 1]);
        if ($product) {
            $product->delete();
        }
        // Ensure /Products folder exists
        \Pimcore\Model\DataObject\Service::createFolderByPath('/Products')->save();
        $this->service = new ProductManagementService();
    }

    public function testCreateProduct(): void
    {
        $result = $this->service->createOrUpdateProduct('PROD010', 'Test Product', 99.99);
        $this->assertSame('Created', $result['operation']);
        $product = Product::getBySku('PROD010', ['limit' => 1]);
        $this->assertNotNull($product);
        $this->assertEquals('Test Product', $product->getName());
        $this->assertEquals('99.99', $product->getPrice());
    }

    public function testUpdateProduct(): void
    {
        // Create initial product
        $product = new Product();
        $product->setKey('PROD010');
        $product->setParent(\Pimcore\Model\DataObject\Service::createFolderByPath('/Products'));
        $product->setPublished(true);
        $product->setSku('PROD010');
        $product->setName('Initial Product');
        $product->setPrice('99.99');
        $product->save();

        $result = $this->service->createOrUpdateProduct('PROD010', 'Updated Product', 149.99);
        $this->assertSame('Updated', $result['operation']);
        $product = Product::getBySku('PROD010', ['limit' => 1]);
        $this->assertEquals('Updated Product', $product->getName());
        $this->assertEquals('149.99', $product->getPrice());
    }

    public function testEmptySkuThrowsException(): void
    {
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('SKU is required.');
        $this->service->createOrUpdateProduct('', 'Test Product', 99.99);
    }

    public function testNegativePriceThrowsException(): void
    {
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Price cannot be negative.');
        $this->service->createOrUpdateProduct('PROD010', 'Test Product', -10.00);
    }
}