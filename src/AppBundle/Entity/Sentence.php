<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
     */
    private $indexes;

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
}