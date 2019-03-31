<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TenseGuessRepository")
 */
class TenseGuess
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tense")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tense;

    /**
     * @ORM\Column(type="integer")
     */
    private $a2b_ok;

    /**
     * @ORM\Column(type="integer")
     */
    private $a2b_ko;

    /**
     * @ORM\Column(type="integer")
     */
    private $b2a_ok;

    /**
     * @ORM\Column(type="integer")
     */
    private $b2a_ko;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTense(): ?Tense
    {
        return $this->tense;
    }

    public function setTense(?Tense $tense): self
    {
        $this->tense = $tense;

        return $this;
    }

    public function getA2bOk(): ?int
    {
        return $this->a2b_ok;
    }

    public function setA2bOk(int $a2b_ok): self
    {
        $this->a2b_ok = $a2b_ok;

        return $this;
    }

    public function getA2bKo(): ?int
    {
        return $this->a2b_ko;
    }

    public function setA2bKo(int $a2b_ko): self
    {
        $this->a2b_ko = $a2b_ko;

        return $this;
    }

    public function getB2aOk(): ?int
    {
        return $this->b2a_ok;
    }

    public function setB2aOk(int $b2a_ok): self
    {
        $this->b2a_ok = $b2a_ok;

        return $this;
    }

    public function getB2aKo(): ?int
    {
        return $this->b2a_ko;
    }

    public function setB2aKo(int $b2a_ko): self
    {
        $this->b2a_ko = $b2a_ko;

        return $this;
    }

    public function incA2bOk() : self
    {
        $this->a2b_ok = $this->a2b_ok+1;
        return $this;
    }

    public function incA2bKo() : self
    {
        $this->a2b_ko = $this->a2b_ko+1;
        return $this;
    }

    public function incB2aOk() : self
    {
        $this->b2a_ok = $this->b2a_ok+1;
        return $this;
    }

    public function incB2aKo() : self
    {
        $this->b2a_ko = $this->b2a_ko+1;
        return $this;
    }
}
