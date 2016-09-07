<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WordRepository")
 * @ORM\Table(
 *     indexes={
 *          @ORM\Index(name="simple_idx", columns={"simple"}),
 *          @ORM\Index(name="complex_idx", columns={"complex"}),
 *          @ORM\Index(name="pinyin", columns={"pinyin"})
 *     }
 * )
 */
class Word
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $complex;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $simple;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $pinyin;

    /**
     * @ORM\OneToMany(targetEntity="Meaning", mappedBy="word", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var ArrayCollection
     */
    private $meanings;

    /**
     * @ORM\OneToMany(targetEntity="SentenceIndex", mappedBy="word", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var ArrayCollection
     */
    private $sentenceIndexes;

    public function __construct()
    {
        $this->meanings = new ArrayCollection();
        $this->sentenceIndexes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getComplex()
    {
        return $this->complex;
    }

    /**
     * @param string $complex
     */
    public function setComplex($complex)
    {
        $this->complex = $complex;
    }

    /**
     * @return string
     */
    public function getSimple()
    {
        return $this->simple;
    }

    /**
     * @param string $simple
     */
    public function setSimple($simple)
    {
        $this->simple = $simple;
    }

    /**
     * @return string
     */
    public function getPinyin()
    {
        return $this->pinyin;
    }

    /**
     * @param string $pinyin
     */
    public function setPinyin($pinyin)
    {
        $this->pinyin = $pinyin;
    }

    /**
     * @return ArrayCollection
     */
    public function getMeanings()
    {
        return $this->meanings;
    }

    /**
     * @param ArrayCollection $meanings
     */
    public function setMeanings($meanings)
    {
        $this->meanings = $meanings;
    }

    /**
     * @return ArrayCollection
     */
    public function getSentenceIndexes()
    {
        return $this->sentenceIndexes;
    }

    /**
     * @param ArrayCollection $sentenceIndexes
     */
    public function setSentenceIndexes($sentenceIndexes)
    {
        $this->sentenceIndexes = $sentenceIndexes;
    }
}