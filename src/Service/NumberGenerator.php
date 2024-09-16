<?php

namespace App\Service;

class NumberGenerator {
  private int $maxnum;

  public function __construct($maxnum) {
    $this->maxnum = $maxnum;
  }

  public function getRandomNumber() {
    $number = random_int(0, $this->maxnum);
    return $number;
  }
}