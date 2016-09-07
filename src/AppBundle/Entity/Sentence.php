<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SentenceRepository")
 * @ORM\Table()
 */
class Sentence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $english;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $mandarin;

    /**
     * @ORM\OneToMany(targetEntity="SentenceIndex", mappedBy="sentence", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var ArrayCollection
     */
    private $indexes;

    public function __construct()
    {
        $this->indexes = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEnglish()
    {
        return $this->english;
    }

    /**
     * @param string $english
     */
    public function setEnglish($english)
    {
        $this->english = $english;
    }

    /**
     * @return string
     */
    public function getMandarin()
    {
        return $this->mandarin;
    }

    /**
     * @param string $mandarin
     */
    public function setMandarin($mandarin)
    {
        $this->mandarin = $mandarin;
    }

    /**
     * @return ArrayCollection
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * @param SentenceIndex $index
     */
    public function addIndex(SentenceIndex $index)
    {
        $this->indexes->add($index);
        $index->setSentence($this);
    }

    /**
     * @param SentenceIndex $index
     */
    public function removeIndex(SentenceIndex $index)
    {
        $this->indexes->removeElement($index);
        $index->setSentence(null);
    }

    public function clearIndex() {
        foreach ($this->indexes as $index) {
            $this->removeIndex($index);
        }
    }
}