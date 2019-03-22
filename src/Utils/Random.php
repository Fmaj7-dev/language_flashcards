<?php

namespace App\Utils;

class Random
{
  public static function ExponentialDistribution($lam, $domain) 
  {
    $rand_value = mt_rand() / mt_getrandmax();
    $actual_value = -log(1-$rand_value)/$lam;
    $int_value = round($actual_value-0.5);

    return $int_value;
  }
}

