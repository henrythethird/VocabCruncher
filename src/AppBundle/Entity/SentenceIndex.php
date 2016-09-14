<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class SentenceIndex
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sentence", inversedBy="indexes")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     * @var Sentence
     */
    private $sentence;

    /**
     * @ORM\ManyToMany(targetEntity="Word", inversedBy="sentenceIndexes")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     * @var ArrayCollection
     */
    private $words;

    /**
     * @ORM\Column(type="integer", name="sindex")
     * @var $index
     */
    private $index;

    public function __construct()
    {
        $this->words = new ArrayCollection();
    }

    /**
     * @return Sentence
     */
    public function getSentence()
    {
        return $this->sentence;
    }

    /**
     * @param Sentence $sentence
     */
    public function setSentence($sentence)
    {
        $this->sentence = $sentence;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param int $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * @return ArrayCollection
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @param ArrayCollection $words
     */
    public function setWords($words)
    {
        $this->words = $words;
    }
}