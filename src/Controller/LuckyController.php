<?php

namespace App\Controller;

use App\Service\MessageGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LuckyController extends AbstractController {
  #[Route('/sansli/sayi')]
  public function number(MessageGenerator $messageGenerator): Response {
    $msg = $messageGenerator->numberMessage();

    return $this->render('lucky/number.html.twig', [ 'msg' => $msg ]);
  }
}