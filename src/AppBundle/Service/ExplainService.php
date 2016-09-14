<?php

namespace AppBundle\Service;

use AppBundle\Entity\Word;
use Doctrine\ORM\EntityManager;

class ExplainService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ExplainService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $searchTerm
     * @return array
     */
    public function explain($searchTerm)
    {
        $length = 0;
        $returnArray = [];

        while ($length < mb_strlen($searchTerm)) {
            $length += $this->backtrackQuery(
                mb_substr($searchTerm, $length),
                $length,
                $returnArray
            );
        }

        return $returnArray;
    }

    public function separateTerms($searchTerm)
    {
        $separatedSearchTerms = [];
        for ($length = mb_strlen($searchTerm); $length > 0; $length--) {
            $separatedSearchTerms[] = mb_substr($searchTerm, 0, $length);
        }
        return $separatedSearchTerms;
    }

    /**
     * @param string $searchTerm
     * @return array
     */
    public function query($searchTerm)
    {
        return $this->entityManager
            ->getRepository(Word::class)
            ->findBySimpleOrComplex($searchTerm);
    }

    /**
     * Returns the length of the matched multibyte string
     * @param string $searchTerm
     * @param int $position
     * @param array &$returnArray
     * @return int
     */
    public function backtrackQuery($searchTerm, $position, &$returnArray)
    {
        for ($length = mb_strlen($searchTerm); $length > 0; $length--) {
            $result = $this->query(mb_substr($searchTerm, 0, $length));

            if (empty($result)) continue;

            $returnArray[$position] = $result;

            return $length;
        }

        $returnArray[$position] = null;
        return 1;
    }
}