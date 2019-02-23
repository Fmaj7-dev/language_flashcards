<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Vocabulary
 *
 * @ORM\Table(name="vocabulary", indexes={@ORM\Index(name="language_a", columns={"language_a"}), @ORM\Index(name="language_b", columns={"language_b"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\VocabularyRepository")
 */
class Vocabulary
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
     * @var string
     *
     * @ORM\Column(name="word_a", type="text", length=65535, nullable=false)
     */
    private $wordA;

    /**
     * @var string
     *
     * @ORM\Column(name="word_b", type="text", length=65535, nullable=false)
     */
    private $wordB;

    /**
     * @var bool
     *
     * @ORM\Column(name="level", type="boolean", nullable=false)
     */
    private $level;

    /**
     * @var \Languages
     *
     * @ORM\ManyToOne(targetEntity="Languages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_a", referencedColumnName="id")
     * })
     */
    private $languageA;

    /**
     * @var \Languages
     *
     * @ORM\ManyToOne(targetEntity="Languages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_b", referencedColumnName="id")
     * })
     */
    private $languageB;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Users", mappedBy="vocabulary")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWordA(): ?string
    {
        return $this->wordA;
    }

    public function setWordA(string $wordA): self
    {
        $this->wordA = $wordA;

        return $this;
    }

    public function getWordB(): ?string
    {
        return $this->wordB;
    }

    public function setWordB(string $wordB): self
    {
        $this->wordB = $wordB;

        return $this;
    }

    public function getLevel(): ?bool
    {
        return $this->level;
    }

    public function setLevel(bool $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLanguageA(): ?Languages
    {
        return $this->languageA;
    }

    public function setLanguageA(?Languages $languageA): self
    {
        $this->languageA = $languageA;

        return $this;
    }

    public function getLanguageB(): ?Languages
    {
        return $this->languageB;
    }

    public function setLanguageB(?Languages $languageB): self
    {
        $this->languageB = $languageB;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Users $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->addVocabulary($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            $user->removeVocabulary($this);
        }

        return $this;
    }

}
