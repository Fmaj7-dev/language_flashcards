<?php

namespace App\Utils;

class VerbResult
{
  #tense id
  protected $tense_guess_id;

  protected $value;
  protected $tense_name;
  protected $infinitive;

  function __construct($result) 
  {
      $this->tense_guess_id = $result["tense_guess_id"];
      $this->value = $result["value"];
      $this->tense_name = $result["tense_name"];
      $this->infinitive = $result["infinitive"];
  }

  public function getTenseiGuessId()
  {
      return $this->tense_guess_id;
  }

  public function getValue()
  {
      return $this->value;
  }

  public function getTenseName()
  {
      return $this->tense_name;
  }

  public function getInfinitive()
  {
      return $this->infinitive;
  }
}

