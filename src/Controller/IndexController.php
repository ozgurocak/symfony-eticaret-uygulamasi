<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;

class IndexController extends AbstractController
{
    #[Route('/{page}', name: 'app_products')]
    public function index(ProductRepository $productRepository, int $page = 1): Response
    {
        $products = $productRepository->getAllProductsPaged($page);
        $pagec = $productRepository->getProductCount() / 10;
        $pagecount = (is_float($pagec)) ? (int)($pagec)+1 : $pagec;

        return $this->render('index/index.html.twig', [
            'products' => $products,
            'page' => $page,
            'page_count' => $pagecount,
            'store_title' => 'Symfony E-Ticaret Projesi',
            'store_description' => 'Deneme'
        ]);
    }
}
