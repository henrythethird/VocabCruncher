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
    public function findBySimpleOrComplex($searchTerm)
    {
        return $this->createQueryBuilder("word")
            ->where("word.simple = :searchTerm")
            ->orWhere("word.complex = :searchTerm")
            ->setParameter("searchTerm", $searchTerm)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $searchTerm
     * @return bool
     */
    public function hasSimpleOrComplex($searchTerm)
    {
        return $this->createQueryBuilder("word")
            ->select("COUNT(word.id)")
            ->where("word.simple = :searchTerm")
            ->orWhere("word.complex = :searchTerm")
            ->setParameter("searchTerm", $searchTerm)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    /**
     * @param array $subTerms
     * @return array
     */
    public function optimizedFindAllSubmatch(array $subTerms)
    {
        return $this->createQueryBuilder("word")
            ->where("word.simple IN (:searchTerms)")
            ->orWhere("word.complex IN (:searchTerms)")
            ->orderBy("word.length", "DESC")
            ->setParameter("searchTerms", $subTerms)
            ->getQuery()
            ->getResult();
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
            ->orderBy("word.length")
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