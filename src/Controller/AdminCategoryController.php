<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Category;
use App\Form\Type\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminCategoryController extends AbstractController
{
    #[Route('/admin/add-category', name: 'add_category')]
    public function addCategory(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response
    {
        $session = $request->getSession();
        $userSesh = $session->get('userID', null);
        if(is_null($userSesh)) {
            header('Refresh: 0; /admin/login');
            return new Response(" ");
        }

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $slug = $slugger->slug($category->getName())->lower();
            $category->setSlug($slug);

            $entityManager->persist($category);
            $entityManager->flush();
            header('Refresh: 1; /admin');
            return new Response('Kategori eklendi. Yönlendiriliyorsunuz...');
        }

        return $this->render('admin/form.html.twig', [
            'form_title' => 'Kategori Ekle',
            'form' => $form
        ]);
    }

    #[Route('/admin/category-list', name: 'category_list')]
    public function categoryList(Request $request, CategoryRepository $categoryRepository): Response
    {
        $session = $request->getSession();
        $userSesh = $session->get('userID', null);
        if(is_null($userSesh)) {
            header('Refresh: 0; /admin/login');
            return new Response(" ");
        }

        $categories = $categoryRepository->findAll();

        return $this->render('admin/category-list.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/edit-category/{id}', name: 'edit_category')]
    public function updateCategory(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        Category $category
    ): Response
    {
        $session = $request->getSession();
        $userSesh = $session->get('userID', null);
        if(is_null($userSesh)) {
            header('Refresh: 0; /admin/login');
            return new Response(" ");
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $slug = $slugger->slug($category->getName())->lower();
            $category->setSlug($slug);

            $entityManager->persist($category);
            $entityManager->flush();
            header('Refresh: 1; /admin');
            return new Response('Kategori düzenlendi. Yönlendiriliyorsunuz...');
        }

        return $this->render('admin/form.html.twig', [
            'form_title' => $category->getName().' Kategorisini Düzenle',
            'form' => $form
        ]);
    }

    #[Route('/admin/delete-category/{id}', name: 'confirm_delete_category', methods: ['GET', 'HEAD'])]
    public function confirmDeleteCategory(Request $request, Category $category): Response
    {
        $session = $request->getSession();
        $userSesh = $session->get('userID', null);
        if(is_null($userSesh)) {
            header('Refresh: 0; /admin/login');
            return new Response(" ");
        }

        return $this->render('admin/delete.html.twig', [
            'deletion_title' => $category->getName().' Kategorisini',
            'item' => $category,
            'type' => 'category'
        ]);
    }

    #[Route('/admin/delete-category/{id}', name: 'delete_category', methods: ['POST'])]
    public function deleteCategory(Request $request, EntityManagerInterface $entityManager, Category $category): Response
    {
        $session = $request->getSession();
        $userSesh = $session->get('userID', null);
        if(is_null($userSesh)) {
            header('Refresh: 0; /admin/login');
            return new Response(" ");
        }

        $entityManager->remove($category);
        $entityManager->flush();

        header('Refresh: 1; /admin');
        return new Response('Kategori silindi. Yönlendiriliyorsunuz...');
    }
}