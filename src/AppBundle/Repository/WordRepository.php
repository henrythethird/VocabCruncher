<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class WordRepository extends EntityRepository
{
    /**
     * @param string $searchTerm
     * @return int
     */
    public function findOneBySimpleOrComplex($searchTerm)
    {
        return $this->createQueryBuilder("word")
            ->where("word.simple = :searchTerm")
            ->orWhere("word.complex = :searchTerm")
            ->setParameter("searchTerm", $searchTerm)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function dictionarySearch($searchTerm)
    {
        return $this->createQueryBuilder("word")
            ->where("word.simple LIKE :searchTerm")
            ->orWhere("word.complex LIKE :searchTerm")
            ->orWhere("word.pinyin LIKE :searchTerm")
            ->setParameter("searchTerm", $searchTerm)
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }
}