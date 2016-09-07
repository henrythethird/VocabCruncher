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
}