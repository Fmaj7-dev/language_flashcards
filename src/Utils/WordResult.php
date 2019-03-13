<?php

namespace App\Utils;

class WordResult
{
    protected $id;

    protected $word_a;
    protected $word_b;

    function __construct($result) 
    {
        $this->id = $result["id"];
        $this->word_a = $result["word_a"];
        $this->word_b = $result["word_b"];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getWordA()
    {
        return $this->word_a;
    }

    public function getWordB()
    {
        return $this->word_b;
    }
}

