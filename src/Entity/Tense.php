<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TenseRepository")
 */
class Tense
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
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TenseName")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tensename;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Verb")
     * @ORM\JoinColumn(nullable=false)
     */
    private $verb;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getTensename(): ?TenseName
    {
        return $this->tensename;
    }

    public function setTensename(?TenseName $tensename): self
    {
        $this->tensename = $tensename;

        return $this;
    }

    public function getVerb(): ?Verb
    {
        return $this->verb;
    }

    public function setVerb(?Verb $verb): self
    {
        $this->verb = $verb;

        return $this;
    }
}
