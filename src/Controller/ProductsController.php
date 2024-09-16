<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Repository\CategoryRepository;

class ProductsController extends AbstractController
{
    #[Route('/category/{category_slug}/{page}', name: 'products_category')]
    public function productsCategory(ProductRepository $productRepository, CategoryRepository $categoryRepository, string $category_slug, int $page = 1): Response
    {
        $category = $categoryRepository->findBySlug($category_slug);

        $products = $productRepository->getProductsPagedByCategory($page, $category);
        $pagec = $productRepository->getProductCountByCategory($category) / 10;
        $pagecount = (is_float($pagec)) ? (int)($pagec)+1 : $pagec;

        return $this->render('index/index.html.twig', [
            'products' => $products,
            'page' => $page,
            'page_count' => $pagecount,
            'store_title' => $category->getName(),
            'store_description' => $category->getDescription()
        ]);
    }

    #[Route('/products/{slug}', name: 'product_details')]
    public function productDetails(string $slug, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findBySlug($slug);

        return $this->render('products/product.html.twig', [
            'product' => $product
        ]);
    }
}
