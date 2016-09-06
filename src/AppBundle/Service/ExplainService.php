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

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $string
     * @return array
     */
    public function explain($string)
    {
        $length = 0;
        $returnArray = [];

        while ($length < mb_strlen($string)) {
            $length += $this->backtrackQuery(
                mb_substr($string, $length),
                $length,
                $returnArray
            );
        }

        return $returnArray;
    }

    /**
     * @param $string
     * @return null|object
     */
    public function query($string)
    {
        return $this->entityManager
            ->getRepository(Word::class)
            ->findOneBy(
                [
                    'simple' => $string,
                ]
            );
    }

    /**
     * @param $string
     * @param $position
     * @param $returnArray
     * @return int
     */
    public function backtrackQuery($string, $position, &$returnArray)
    {
        for ($length = mb_strlen($string); $length > 0; $length--) {
            $result = $this->query(mb_substr($string, 0, $length));

            if (null === $result) continue;

            $returnArray[$position] = $result;

            return $length;
        }

        $returnArray[$position] = null;
        return 1;
    }
}