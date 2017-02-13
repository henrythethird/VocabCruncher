<?php

namespace AppBundle\Service;

use AppBundle\Entity\Word;
use AppBundle\Repository\WordRepository;
use AppBundle\Value\SearchValue;
use Doctrine\ORM\EntityManager;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class SearchService
{
    /**
     * @var WordRepository
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

    /**
     * @var TransformedFinder
     */
    private $englishWordSearch;

    public function __construct(EntityManager $entityManager, ExplainService $explain, TransformedFinder $englishSearch)
    {
        $this->repository = $entityManager->getRepository(Word::class);
        $this->explain = $explain;
        $this->pinyinUtil = new PinyinService();
        $this->englishWordSearch = $englishSearch;
    }

    /**
     * @param string $searchTerm
     * @param bool $preferChinese
     * @return SearchValue
     */
    public function search($searchTerm, $preferChinese = false)
    {
        if ($this->pinyinUtil->containsChinese($searchTerm)) {
            return $this->constructChineseSearchValue(
                $this->chineseOrExplain($searchTerm)
            );
        }
        if ($preferChinese) {
            return $this->chineseFirst($searchTerm);
        } else {
            return $this->englishFirst($searchTerm);
        }
    }

    /**
     * @param string $searchTerm
     * @return Word[]
     */
    public function chineseOrExplain($searchTerm)
    {
        $result = $this->searchChinese($searchTerm);
        if (!empty($result)) {
            return $result;
        }

        $explanations = $this->explain->explain($searchTerm);
        $flattenedExplanations = [];

        foreach ($explanations as $explanation) {
            if (!is_array($explanation)) {
                continue;
            }
            $flattenedExplanations = array_merge($flattenedExplanations, $explanation);
        }

        return array_filter($flattenedExplanations);
    }

    /**
     * @param string $searchTerm
     * @return SearchValue
     */
    public function chineseFirst($searchTerm)
    {
        $result = $this->searchChinese($searchTerm);
        if (!empty($result)) {
            return $this->constructChineseSearchValue($result);
        }

        return $this->constructEnglishSearchValue(
            $this->searchEnglish($searchTerm)
        );
    }

    /**
     * @param string $searchTerm
     * @return SearchValue
     */
    public function englishFirst($searchTerm)
    {
        $result = $this->searchEnglish($searchTerm);
        if (!empty($result)) {
            return $this->constructEnglishSearchValue($result);
        }

        return $this->constructChineseSearchValue(
            $this->searchChinese($searchTerm)
        );
    }

    private function constructEnglishSearchValue($results)
    {
        return new SearchValue($results, SearchValue::PREFER_ENGLISH);
    }

    private function constructChineseSearchValue($results)
    {
        return new SearchValue($results, SearchValue::PREFER_CHINESE);
    }

    private function searchEnglish($searchTerm)
    {
        return $this->repository->dictionarySearchEnglish($searchTerm);
    }

    private function searchChinese($searchTerm)
    {
        return $this->repository->dictionarySearchChinese($searchTerm);
    }
}