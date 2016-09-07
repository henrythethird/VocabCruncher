<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

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
}