<?php

namespace Ramyakant\ProductManagementBundle\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends FrontendController
{
    /**
     * @Route("/ramyakant_product_management")
     */
    public function indexAction(Request $request): Response
    {
        return new Response('Hello world from ramyakant_product_management');
    }
}
