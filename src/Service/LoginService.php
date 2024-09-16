<?php

namespace App\Service;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class LoginService {

  public function __construct(private RequestStack $requestStack, private UserRepository $userRepository)
  { }

  public function login($username, $password): bool {
    $session = $this->requestStack->getSession();
    
    $user = $this->userRepository->findOneByUsernamePassword($username, md5($password));

    if(is_null($user)) {
        return false;
    }

    $session->set('userID', $user->getId());
    $session->set('username', $user->getUsername());
    return true;
  }
}