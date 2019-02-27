<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Guess
 *
 * @ORM\Table(name="guess", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="vocabulary_id", columns={"vocabulary_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\GuessRepository")
 */
class Guess
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="a2b_ok", type="integer", nullable=false)
     */
    private $a2bOk;

    /**
     * @var int
     *
     * @ORM\Column(name="a2b_ko", type="integer", nullable=false)
     */
    private $a2bKo;

    /**
     * @var int
     *
     * @ORM\Column(name="b2a_ok", type="integer", nullable=false)
     */
    private $b2aOk;

    /**
     * @var int
     *
     * @ORM\Column(name="b2a_ko", type="integer", nullable=false)
     */
    private $b2aKo;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Vocabulary
     *
     * @ORM\ManyToOne(targetEntity="Vocabulary")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vocabulary_id", referencedColumnName="id")
     * })
     */
    private $vocabulary;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getA2bOk(): ?int
    {
        return $this->a2bOk;
    }

    public function setA2bOk(int $a2bOk): self
    {
        $this->a2bOk = $a2bOk;

        return $this;
    }

    public function getA2bKo(): ?int
    {
        return $this->a2bKo;
    }

    public function setA2bKo(int $a2bKo): self
    {
        $this->a2bKo = $a2bKo;

        return $this;
    }

    public function getB2aOk(): ?int
    {
        return $this->b2aOk;
    }

    public function setB2aOk(int $b2aOk): self
    {
        $this->b2aOk = $b2aOk;

        return $this;
    }

    public function getB2aKo(): ?int
    {
        return $this->b2aKo;
    }

    public function setB2aKo(int $b2aKo): self
    {
        $this->b2aKo = $b2aKo;

        return $this;
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

    public function getVocabulary(): ?Vocabulary
    {
        return $this->vocabulary;
    }

    public function setVocabulary(?Vocabulary $vocabulary): self
    {
        $this->vocabulary = $vocabulary;

        return $this;
    }


}
