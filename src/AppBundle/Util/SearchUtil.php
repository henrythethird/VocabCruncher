<?php

namespace AppBundle\Util;

use AppBundle\Repository\WordRepository;
use AppBundle\Service\ExplainService;
use Doctrine\ORM\EntityRepository;

class SearchUtil
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var PinyinUtil
     */
    private $pinyinUtil;

    /**
     * @var ExplainService
     */
    private $explain;

    public function __construct(WordRepository $entityRepository, ExplainService $explain)
    {
        $this->repository = $entityRepository;
        $this->explain = $explain;
        $this->pinyinUtil = new PinyinUtil();
    }

    /**
     * @param string $searchTerm
     * @param bool $preferChinese
     * @return array
     */
    public function search($searchTerm, $preferChinese = false)
    {
        if ($this->pinyinUtil->containsChinese($searchTerm)) {
            return $this->chineseOrExplain($searchTerm);
        }
        if ($preferChinese) {
            return $this->chineseFirst($searchTerm);
        } else {
            return $this->englishFirst($searchTerm);
        }
    }

    /**
     * @param string $searchTerm
     * @return array
     */
    public function chineseOrExplain($searchTerm)
    {
        $result = $this->repository->dictionarySearchChinese($searchTerm);
        if (!empty($result)) {
            return $result;
        }

        return $this->explain->explain($searchTerm);
    }

    /**
     * @param string $searchTerm
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
     * @param string $searchTerm
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