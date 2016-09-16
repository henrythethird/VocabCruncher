<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SentenceRepository extends EntityRepository
{
    const LATEST_LIMIT = 20;
    const SEARCH_LIMIT = 20;
    const SENTENCE_LIMIT = 20;

    public function findLatest()
    {
        return $this->findBy([], [], self::LATEST_LIMIT);
    }

    public function search($searchTerm)
    {
        return $this->createQueryBuilder("sentence")
            ->where("sentence.mandarin LIKE :searchTerm")
            ->orWhere("sentence.english LIKE :searchTerm")
            ->setParameter(":searchTerm", "%$searchTerm%")
            ->setMaxResults(self::SEARCH_LIMIT)
            ->getQuery()
            ->getResult();
    }

    public function containsChinese($searchTerm)
    {
        return $this->createQueryBuilder("sentence")
            ->where("sentence.mandarin LIKE :searchTerm")
            ->setParameter(":searchTerm", "%$searchTerm%")
            ->setMaxResults(self::SENTENCE_LIMIT)
            ->getQuery()
            ->getResult();
    }
}