<?php

namespace AppBundle\Util;

use AppBundle\Entity\Sentence;
use Doctrine\ORM\EntityRepository;

class SentenceIndexUtil
{
    /**
     * @var Sentence
     */
    private $sentence;

    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(Sentence $sentence, EntityRepository $repository)
    {
        $this->sentence = $sentence;
        $this->repository = $repository;
    }

    public function analyze()
    {
        $this->analyzeRecursive($this->sentence
        ->getMandarin()
        );
    }

    public function analyzeRecursive($sentenceFragment)
    {

    }
}