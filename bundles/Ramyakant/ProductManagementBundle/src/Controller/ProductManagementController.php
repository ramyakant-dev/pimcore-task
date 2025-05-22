<?php

namespace Ramyakant\ProductManagementBundle\Controller;

use Ramyakant\ProductManagementBundle\Service\ProductManagementService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductManagementController
{
    private ProductManagementService $productManagementService;

    public function __construct(ProductManagementService $productManagementService)
    {
        $this->productManagementService = $productManagementService;
    }

    /**
     * @param Request $request 
     * @return JsonResponse
     * @throws BadRequestHttpException
     * @throws Exception
     */
    public function updateProductAction(Request $request): JsonResponse
    {
        try {
            // Check Content-Type to determine request format
            $contentType = $request->headers->get('Content-Type');
            if (stripos($contentType, 'application/json') !== false) {
                // Handle API request
                $data = json_decode($request->getContent(), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid JSON payload');
                }
            } else {
                // Handle form
                $data = $request->request->all();
            }

            // Validate input if SKU or Price is Empty.
            if (!isset($data['sku'], $data['price'])) {
                throw new BadRequestHttpException('Mandatory fields: sku and price.');
            }

            // Get the parameters
            $sku = $data['sku'];
            $name = $data['name'] ?? null;
            $price = (float) $data['price'];

            // Call service to create or Update Products
            $product = $this->productManagementService->createOrUpdateProduct($sku, $name, $price);

            return new JsonResponse([
                'success' => true,
                'message' => sprintf( 'Product with SKU %s %s.', $sku, $product["operation"] ),
                'product' => [
                    'id' => $product["product"]->getId(),
                    'sku' => $product["product"]->getSku(),
                    'name' => $product["product"]->getName(),
                    'price' => $product["product"]->getPrice(),
                ],
            ], 200);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}