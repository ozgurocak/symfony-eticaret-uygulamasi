<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\Type\RegisterType;
use App\Form\Type\LoginType;
use App\Service\LoginService;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();

        $user = $session->get('userID', null);
        if(is_null($user)) {
            header('Refresh: 0; /admin/login');
            return new Response(" ");
        }

        return $this->render('admin/index.html.twig', [
            'username' => $session->get('username'),
        ]);
    }

    #[Route('/admin/register', name: 'register')]
    public function register(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        $user = new User();
        $form = $this->createForm(RegisterType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $rawPass = $formData['password'];
            $rawPassR = $formData['password_repeat'];
            if(!($rawPass === $rawPassR))
                return new JsonResponse(['success' => false, 'message' => 'Passwords do not match.']); 
            $user->setUsername($formData['username']);
            $user->setPassword(md5($rawPass));
            $entityManager->persist($user);
            $entityManager->flush();
            header('Refresh: 2; /admin');
            return new Response('Kayıt başarılı. Giriş ekranına yönlendiriliyorsunuz...');
        }

        return $this->render('admin/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/login', name: 'login')]
    public function login(Request $request, LoginService $loginService): Response
    {
        $session = $request->getSession();
        $userSesh = $session->get('userID', null);
        if(!is_null($userSesh)) {
            header('Refresh: 0; /admin');
            return new Response(" ");
        }

        $form = $this->createForm(LoginType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $login = $loginService->login($formData['username'], $formData['password']);
            if(!$login) return new Response(['Kullanıcı adı veya şifre hatalı.']);
            header('Refresh: 2; /admin');
            return new Response('Giriş yaptınız. Panele yönlendiriliyorsunuz...');
        }

        return $this->render('admin/login.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('userID', null);
        $session->set('username', null);
        header('Refresh: 0; /admin/login');

        return new Response(' ');
    }
}
