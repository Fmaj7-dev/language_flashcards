<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="users", indexes={@ORM\Index(name="native_language", columns={"native_language"})})
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @ORM\Entity
 */
class Users
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
     * @ORM\Column(name="name", type="text", length=65535, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="text", length=65535, nullable=false)
     */
    private $email;

    /**
     * @var \Languages
     *
     * @ORM\ManyToOne(targetEntity="Languages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="native_language", referencedColumnName="id")
     * })
     */
    private $nativeLanguage;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Vocabulary", inversedBy="user")
     * @ORM\JoinTable(name="guess",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="vocabulary_id", referencedColumnName="id")
     *   }
     * )
     */
    private $vocabulary;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Languages", inversedBy="user")
     * @ORM\JoinTable(name="user_language",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     *   }
     * )
     */
    private $language;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->vocabulary = new \Doctrine\Common\Collections\ArrayCollection();
        $this->language = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNativeLanguage(): ?Languages
    {
        return $this->nativeLanguage;
    }

    public function setNativeLanguage(?Languages $nativeLanguage): self
    {
        $this->nativeLanguage = $nativeLanguage;

        return $this;
    }

    /**
     * @return Collection|Vocabulary[]
     */
    public function getVocabulary(): Collection
    {
        return $this->vocabulary;
    }

    public function addVocabulary(Vocabulary $vocabulary): self
    {
        if (!$this->vocabulary->contains($vocabulary)) {
            $this->vocabulary[] = $vocabulary;
        }

        return $this;
    }

    public function removeVocabulary(Vocabulary $vocabulary): self
    {
        if ($this->vocabulary->contains($vocabulary)) {
            $this->vocabulary->removeElement($vocabulary);
        }

        return $this;
    }

    /**
     * @return Collection|Languages[]
     */
    public function getLanguage(): Collection
    {
        return $this->language;
    }

    public function addLanguage(Languages $language): self
    {
        if (!$this->language->contains($language)) {
            $this->language[] = $language;
        }

        return $this;
    }

    public function removeLanguage(Languages $language): self
    {
        if ($this->language->contains($language)) {
            $this->language->removeElement($language);
        }

        return $this;
    }

}
