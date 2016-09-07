<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Word;
use Doctrine\ORM\EntityRepository;

class WordRepository extends EntityRepository
{
    const MAX_SEARCH_RESULTS = 20;

    /**
     * @param $searchTerm
     * @return Word|null
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

    /**
     * @param $searchTerm
     * @return array
     */
    public function dictionarySearchChinese($searchTerm)
    {
        return $this->createQueryBuilder("word")
            ->where("word.simple LIKE :searchTerm")
            ->orWhere("word.complex LIKE :searchTerm")
            ->orWhere("word.pinyinAbbr LIKE :searchTerm")
            ->orderBy("word.simple", 'DESC')
            ->addOrderBy("word.simple")
            ->setParameter("searchTerm", "$searchTerm%")
            ->setMaxResults(self::MAX_SEARCH_RESULTS)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $searchTerm
     * @return array
     */
    public function dictionarySearchEnglish($searchTerm)
    {
        return $this->createQueryBuilder("word")
            ->leftJoin("word.meanings", "meaning")
            ->where("meaning.meaning LIKE :searchTerm")
            ->setParameter("searchTerm", "%$searchTerm%")
            ->setMaxResults(self::MAX_SEARCH_RESULTS)
            ->getQuery()
            ->getResult();
    }
}