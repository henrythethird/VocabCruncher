<?php

namespace AppBundle\Util;

use AppBundle\Entity\Sentence;
use AppBundle\Entity\SentenceIndex;
use AppBundle\Service\ExplainService;
use Doctrine\ORM\EntityManager;

class SentenceCompileUtil
{
    /**
     * @var Sentence
     */
    private $sentence;

    /**
     * @var ExplainService
     */
    private $explain;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(Sentence $sentence, ExplainService $explain, EntityManager $entityManager)
    {
        $this->sentence = $sentence;
        $this->explain = $explain;
        $this->entityManager = $entityManager;
    }

    public function compile()
    {
        if ($this->hasIndexes()) {
            throw new \Exception("Sentence already compiled");
        }

        $explanation = $this->explain->explain($this->sentence->getMandarin());

        foreach ($explanation as $index => $word) {
            if (!is_object($word)) {
                continue;
            }

            $sentenceIndex = new SentenceIndex();
            $sentenceIndex->setIndex($index);
            $sentenceIndex->setSentence($this->sentence);
            $sentenceIndex->setWord($word);

            $this->entityManager->persist($sentenceIndex);
        }
        $this->entityManager->flush();
    }

    public function recompile()
    {
        $this->flushIndexes();
        $this->compile();
    }

    public function hasIndexes()
    {
        return !$this->sentence->getIndexes()->isEmpty();
    }

    public function flushIndexes()
    {
        $this->sentence->clearIndex();
        $this->entityManager->persist($this->sentence);
    }
}