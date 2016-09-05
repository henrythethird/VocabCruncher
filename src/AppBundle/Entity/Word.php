<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table()
 */
class Word {
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

	public function __construct() {
		$this->meanings = new ArrayCollection();
	}

	/**
	 * @return string
	 */
	public function getComplex() {
		return $this->complex;
	}

	/**
	 * @param string $complex
	 */
	public function setComplex($complex) {
		$this->complex = $complex;
	}

	/**
	 * @return string
	 */
	public function getSimple() {
		return $this->simple;
	}

	/**
	 * @param string $simple
	 */
	public function setSimple($simple) {
		$this->simple = $simple;
	}

	/**
	 * @return string
	 */
	public function getPinyin() {
		return $this->pinyin;
	}

	/**
	 * @param string $pinyin
	 */
	public function setPinyin($pinyin) {
		$this->pinyin = $pinyin;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getMeanings() {
		return $this->meanings;
	}

	/**
	 * @param ArrayCollection $meanings
	 */
	public function setMeanings($meanings) {
		$this->meanings = $meanings;
	}
}