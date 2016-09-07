<?php

namespace AppBundle\Entity;

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
     * @ORM\ManyToOne(targetEntity="Word", inversedBy="sentenceIndexes")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     * @var Word
     */
    private $word;

    /**
     * @ORM\Column(type="integer", name="sindex")
     * @var $index
     */
    private $index;

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
     * @return mixed
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param mixed $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }
}