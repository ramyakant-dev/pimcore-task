<?php

declare(strict_types=1);

namespace Ramyakant\ProductManagementBundle\Tests\Unit;

use Pimcore\Model\DataObject\Product;
use Ramyakant\ProductManagementBundle\Controller\ProductManagementController;
use Ramyakant\ProductManagementBundle\Service\ProductManagementService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use PHPUnit\Framework\TestCase;

class ProductManagementControllerTest extends TestCase
{
    private ProductManagementController $controller;
    private ProductManagementService $serviceMock;

    protected function setUp(): void
    {
        $this->serviceMock = $this->createMock(ProductManagementService::class);
        $this->controller = new ProductManagementController($this->serviceMock);
    }

    public function testCreateProductWithFormData(): void
    {
        // Mock Request for form-urlencoded data
        $request = $this->createMock(Request::class);
        $request->headers = new ParameterBag(['Content-Type' => 'application/x-www-form-urlencoded']);
        $request->request = new ParameterBag([
            'sku' => 'PROD011',
            'name' => 'Form Product',
            'price' => 99.99
        ]);
        $request->method('getContent')->willReturn('');

        // Mock Product and service response
        $productMock = $this->createMock(Product::class);
        $productMock->method('getId')->willReturn(123);
        $productMock->method('getSku')->willReturn('PROD011');
        $productMock->method('getName')->willReturn('Form Product');
        $productMock->method('getPrice')->willReturn('99.99');

        $this->serviceMock->expects($this->once())
            ->method('createOrUpdateProduct')
            ->with('PROD011', 'Form Product', 99.99)
            ->willReturn(['product' => $productMock, 'operation' => 'Created']);

        // Call updateProductAction
        $response = $this->controller->updateProductAction($request);

        // Assert response
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'success' => true,
                'message' => 'Product with SKU PROD011 Created.',
                'product' => [
                    'id' => 123,
                    'sku' => 'PROD011',
                    'name' => 'Form Product',
                    'price' => '99.99'
                ]
            ]),
            $response->getContent()
        );
    }

    public function testUpdateProductWithJsonData(): void
    {
        // Mock Request for JSON data
        $request = $this->createMock(Request::class);
        $request->headers = new ParameterBag(['Content-Type' => 'application/json']);
        $request->method('getContent')->willReturn(json_encode([
            'sku' => 'PROD011',
            'name' => 'Updated Product',
            'price' => 149.99
        ]));

        // Mock Product and service response
        $productMock = $this->createMock(Product::class);
        $productMock->method('getId')->willReturn(123);
        $productMock->method('getSku')->willReturn('PROD011');
        $productMock->method('getName')->willReturn('Updated Product');
        $productMock->method('getPrice')->willReturn('149.99');

        $this->serviceMock->expects($this->once())
            ->method('createOrUpdateProduct')
            ->with('PROD011', 'Updated Product', 149.99)
            ->willReturn(['product' => $productMock, 'operation' => 'Updated']);

        // Call updateProductAction
        $response = $this->controller->updateProductAction($request);

        // Assert response
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'success' => true,
                'message' => 'Product with SKU PROD011 Updated.',
                'product' => [
                    'id' => 123,
                    'sku' => 'PROD011',
                    'name' => 'Updated Product',
                    'price' => '149.99'
                ]
            ]),
            $response->getContent()
        );
    }

    public function testMissingRequiredFields(): void
    {
        // Mock Request with missing fields
        $request = $this->createMock(Request::class);
        $request->headers = new ParameterBag(['Content-Type' => 'application/x-www-form-urlencoded']);
        $request->request = new ParameterBag(['name' => 'Test Product']);
        $request->method('getContent')->willReturn('');

        // Call updateProductAction
        $response = $this->controller->updateProductAction($request);

        // Assert error response
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['success' => false, 'message' => 'Mandatory fields: sku and price.']),
            $response->getContent()
        );
    }

    public function testInvalidJsonPayload(): void
    {
        // Mock Request with invalid JSON
        $request = $this->createMock(Request::class);
        $request->headers = new ParameterBag(['Content-Type' => 'application/json']);
        $request->method('getContent')->willReturn('invalid json');

        // Call updateProductAction
        $response = $this->controller->updateProductAction($request);

        // Assert error response
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(500, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['success' => false, 'message' => 'An error occurred: Invalid JSON payload']),
            $response->getContent()
        );
    }
}