<?php

namespace AppBundle\Service;

use AppBundle\Entity\Word;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class SearchService
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var PinyinService
     */
    private $pinyinUtil;

    /**
     * @var ExplainService
     */
    private $explain;

    public function __construct(EntityManager $entityManager, ExplainService $explain)
    {
        $this->repository = $entityManager->getRepository(Word::class);
        $this->explain = $explain;
        $this->pinyinUtil = new PinyinService();
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

        return array_filter($this->explain->explain($searchTerm));
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