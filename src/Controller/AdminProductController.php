<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Product;
use App\Form\Type\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminProductController extends AbstractController
{
    #[Route('/admin/add-product', name: 'add_product')]
    public function addProduct(
        Request $request,
        EntityManagerInterface $entityManager,
        #[Autowire('%kernel.project_dir%/public/uploads/images')] string $imagesDirectory,
        SluggerInterface $slugger
    ): Response
    {
        $session = $request->getSession();
        $userSesh = $session->get('userID', null);
        if(is_null($userSesh)) {
            header('Refresh: 0; /admin/login');
            return new Response(" ");
        }

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            
            $productImage = $form->get('image')->getData();
            if($productImage) {
                $originalFilename = pathinfo($productImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename."-".((string) rand(1000, 9999)).".".$productImage->guessExtension();

                try {
                    $productImage->move($imagesDirectory, $newFilename);
                } catch (FileException $e) {
                    echo "Dosya yüklenemedi: ", $e;
                }
                $product->setImageFilename($newFilename);
            }

            $slug = $slugger->slug($product->getName())->lower()."-";
            $slug .= (string) rand(1000, 9999);
            $product->setSlug($slug);

            $entityManager->persist($product);
            $entityManager->flush();
            header('Refresh: 1; /admin');
            return new Response('Ürün eklendi. Yönlendiriliyorsunuz...');
        }

        return $this->render('admin/form.html.twig', [
            'form_title' => 'Ürün Ekle',
            'form' => $form
        ]);
    }

    #[Route('/admin/product-list', name: 'product_list')]
    public function productList(Request $request, ProductRepository $productRepository): Response
    {
        $session = $request->getSession();
        $userSesh = $session->get('userID', null);
        if(is_null($userSesh)) {
            header('Refresh: 0; /admin/login');
            return new Response(" ");
        }

        $products = $productRepository->findAll();

        return $this->render('admin/product-list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/admin/edit-product/{id}', name: 'edit_product')]
    public function updateProduct(
        Request $request,
        EntityManagerInterface $entityManager,
        Product $product,
        #[Autowire('%kernel.project_dir%/public/uploads/images')] string $imagesDirectory,
        SluggerInterface $slugger
    ): Response
    {
        $session = $request->getSession();
        $userSesh = $session->get('userID', null);
        if(is_null($userSesh)) {
            header('Refresh: 0; /admin/login');
            return new Response(" ");
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            
            $productImage = $form->get('image')->getData();
            if($productImage) {
                $originalFilename = pathinfo($productImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename."-".((string) rand(1000, 9999)).".".$productImage->guessExtension();

                try {
                    $productImage->move($imagesDirectory, $newFilename);
                } catch (FileException $e) {
                    echo "Dosya yüklenemedi: ", $e;
                }
                $product->setImageFilename($newFilename);
            }

            $slug = $slugger->slug($product->getName())->lower()."-";
            $slug .= (string) rand(1000, 9999);
            $product->setSlug($slug);

            $entityManager->persist($product);
            $entityManager->flush();
            header('Refresh: 1; /admin');
            return new Response('Ürün düzenlendi. Yönlendiriliyorsunuz...');
        }

        return $this->render('admin/form.html.twig', [
            'form_title' => $product->getName().' Ürününü Düzenle',
            'form' => $form
        ]);
    }

    #[Route('/admin/delete-product/{id}', name: 'confirm_delete_product', methods: ['GET', 'HEAD'])]
    public function confirmDeleteProduct(Request $request, Product $product): Response
    {
        $session = $request->getSession();
        $userSesh = $session->get('userID', null);
        if(is_null($userSesh)) {
            header('Refresh: 0; /admin/login');
            return new Response(" ");
        }

        return $this->render('admin/delete.html.twig', [
            'deletion_title' => $product->getName().' Ürününü',
            'item' => $product,
            'type' => 'product'
        ]);
    }

    #[Route('/admin/delete-product/{id}', name: 'delete_product', methods: ['POST'])]
    public function deleteProduct(Request $request, EntityManagerInterface $entityManager, Product $product): Response
    {
        $session = $request->getSession();
        $userSesh = $session->get('userID', null);
        if(is_null($userSesh)) {
            header('Refresh: 0; /admin/login');
            return new Response(" ");
        }

        $entityManager->remove($product);
        $entityManager->flush();

        header('Refresh: 1; /admin');
        return new Response('Ürün silindi. Yönlendiriliyorsunuz...');
    }
}