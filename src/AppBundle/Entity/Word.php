<?php

namespace AppBundle\Entity;

use AppBundle\Service\PinyinService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WordRepository")
 * @ORM\Table(
 *     indexes={
 *          @ORM\Index(name="length_idx", columns={"length"}),
 *          @ORM\Index(name="simple_idx", columns={"simple"}),
 *          @ORM\Index(name="complex_idx", columns={"complex"}),
 *          @ORM\Index(name="pinyin_idx", columns={"pinyin"}),
 *          @ORM\Index(name="pinyin_abbr_idx", columns={"pinyin_abbr"}),
 *          @ORM\Index(name="composite_idx", columns={"simple", "complex", "pinyin_abbr", "length"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(type="integer")
     * @var int
     */
    private $length;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $pinyin;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $pinyinAbbr;

    /**
     * @ORM\Column(type="integer")
     * @var string
     */
    private $frequency;

    /**
     * @ORM\OneToMany(targetEntity="Meaning", mappedBy="word", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var ArrayCollection
     */
    private $meanings;

    /**
     * @ORM\ManyToMany(targetEntity="SentenceIndex", mappedBy="words", cascade={"persist"})
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param int $frequency
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
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
     * @return mixed
     */
    public function getPinyinAbbr()
    {
        return $this->pinyinAbbr;
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

    /**
     * @ORM\PreFlush()
     */
    public function preFlush()
    {
        $pinyinUtil = new PinyinService();
        $this->pinyinAbbr = $pinyinUtil->fromNumberToPlain($this->pinyin);
        $this->length = mb_strlen($this->getSimple());
    }
}