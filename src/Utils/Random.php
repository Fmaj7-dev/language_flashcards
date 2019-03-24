<?php

namespace App\Utils;

class Random
{
  public static function ExponentialDistribution($lam, $domain) 
  {
    $rand_value = mt_rand() / mt_getrandmax();
    $actual_value = -log(1-$rand_value)/$lam;
    $int_value = round($actual_value-0.5);

    // guarantee we are not out of bounds
    if ($int_value >= $domain)
      return Random::ExponentialDistribution($lam, $domain);

    return $int_value;
  }
}

