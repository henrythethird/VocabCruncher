<?php

namespace AppBundle\Service;

use AppBundle\Entity\Sentence;
use AppBundle\Entity\SentenceIndex;
use Doctrine\ORM\EntityManager;

class SentenceCompileService
{
    /**
     * @var ExplainService
     */
    private $explain;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager, ExplainService $explain)
    {
        $this->explain = $explain;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Sentence $sentence
     * @throws \Exception
     */
    public function compile(Sentence $sentence)
    {
        if ($this->hasIndexes($sentence)) {
            throw new \Exception("Sentence already compiled");
        }

        $explanation = $this->explain->explain($sentence->getMandarin());

        foreach ($explanation as $index => $word) {
            if (!is_object($word)) {
                continue;
            }

            $sentenceIndex = new SentenceIndex();
            $sentenceIndex->setIndex($index);
            $sentenceIndex->setSentence($sentence);
            $sentenceIndex->setWord($word);

            $this->entityManager->persist($sentenceIndex);
        }
        $this->entityManager->flush();
    }

    /**
     * @param Sentence $sentence
     */
    public function recompile(Sentence $sentence)
    {
        $this->flushIndexes($sentence);
        $this->compile($sentence);
    }

    /**
     * @param Sentence $sentence
     * @return bool
     */
    public function hasIndexes(Sentence $sentence)
    {
        return !$sentence->getIndexes()->isEmpty();
    }

    /**
     * @param Sentence $sentence
     */
    public function flushIndexes(Sentence $sentence)
    {
        $sentence->clearIndex();
        $this->entityManager->persist($sentence);
    }
}