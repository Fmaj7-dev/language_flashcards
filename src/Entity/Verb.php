<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VerbRepository")
 */
class Verb
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $infinitive;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInfinitive(): ?string
    {
        return $this->infinitive;
    }

    public function setInfinitive(string $infinitive): self
    {
        $this->infinitive = $infinitive;

        return $this;
    }

    public function __toString(): ?string
    {
        return $this->infinitive;
    }
}
