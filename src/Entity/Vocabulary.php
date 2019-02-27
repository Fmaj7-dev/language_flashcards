<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vocabulary
 *
 * @ORM\Table(name="vocabulary", indexes={@ORM\Index(name="language_a", columns={"language_a"}), @ORM\Index(name="language_b", columns={"language_b"})})
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
     * @var int
     *
     * @ORM\Column(name="level", type="smallint", nullable=false)
     */
    private $level;

    /**
     * @var \Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_a", referencedColumnName="id")
     * })
     */
    private $languageA;

    /**
     * @var \Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_b", referencedColumnName="id")
     * })
     */
    private $languageB;

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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLanguageA(): ?Language
    {
        return $this->languageA;
    }

    public function setLanguageA(?Language $languageA): self
    {
        $this->languageA = $languageA;

        return $this;
    }

    public function getLanguageB(): ?Language
    {
        return $this->languageB;
    }

    public function setLanguageB(?Language $languageB): self
    {
        $this->languageB = $languageB;

        return $this;
    }


}
