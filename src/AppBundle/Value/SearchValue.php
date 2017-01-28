<?php

namespace AppBundle\Value;

use AppBundle\Entity\Word;

class SearchValue
{
    const PREFER_ENGLISH = 0;
    const PREFER_CHINESE = 1;

    /**
     * @var Word[]
     */
    private $results;

    /**
     * @var int
     */
    private $search_preference;

    /**
     * SearchValue constructor.
     * @param Word[] $results
     * @param int $search_preference
     */
    public function __construct($results, $search_preference)
    {
        $this->results = $results;
        $this->search_preference = $search_preference;
    }

    /**
     * @return Word[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return int
     */
    public function getSearchPreference()
    {
        return $this->search_preference;
    }
}