<?php

namespace AppBundle\Util;

class PinyinUtil
{
    /**
     * @param string $numberedNotation
     * @return string
     */
    public function fromNumberToPlain($numberedNotation)
    {
        return preg_replace('/[0-9 ]/', '', $numberedNotation);
    }

    /**
     * @param string $searchString
     * @return bool
     */
    public function containsChinese($searchString)
    {
        return preg_match('/\p{Han}+/u', $searchString) === 1;
    }

    /**
     * @param string $searchString
     * @return string
     */
    public function filterChinese($searchString)
    {
        return preg_replace('/\P{Han}/u', '', $searchString);
    }
}