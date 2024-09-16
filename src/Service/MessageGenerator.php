<?php

namespace App\Service;

class MessageGenerator {
  private $number;

  public function __construct(NumberGenerator $numberGenerator) {
    $this->number = $numberGenerator->getRandomNumber();
  }

  public function numberMessage() {
    $message = 'Şanslı sayınız '.$this->number.'';
    return $message;
  }
}