<?php

namespace AppBundle\Util;

use AppBundle\Repository\WordRepository;
use Doctrine\ORM\EntityRepository;

class SearchUtil
{
    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(WordRepository $entityRepository)
    {
        $this->repository = $entityRepository;
    }

    /**
     * @param $searchTerm
     * @param bool $preferChinese
     * @return array
     */
    public function search($searchTerm, $preferChinese = false)
    {
        if ($preferChinese) {
            return $this->chineseFirst($searchTerm);
        } else {
            return $this->englishFirst($searchTerm);
        }
    }

    /**
     * @param $searchTerm
     * @return array
     */
    public function chineseFirst($searchTerm)
    {
        $result = $this->repository->dictionarySearchChinese($searchTerm);
        if (!empty($result)) {
            return $result;
        }

        return $this->repository->dictionarySearchEnglish($searchTerm);
    }

    /**
     * @param $searchTerm
     * @return array
     */
    public function englishFirst($searchTerm)
    {
        $result = $this->repository->dictionarySearchEnglish($searchTerm);
        if (!empty($result)) {
            return $result;
        }

        return $this->repository->dictionarySearchChinese($searchTerm);
    }
}