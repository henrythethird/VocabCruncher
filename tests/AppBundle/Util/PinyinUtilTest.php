<?php

namespace Tests\AppBundle\Util;

use AppBundle\Util\PinyinUtil;

class PinyinUtilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider numberPlainProvider
     * @param $numberedString
     * @param $expected
     */
    public function testConvertsCorrectlyFromNumberToPlain($numberedString, $expected)
    {
        $util = new PinyinUtil();
        $this->assertEquals($expected, $util->fromNumberToPlain($numberedString));
    }

    public function numberPlainProvider()
    {
        return [
            ['zhong1 guo3 ren2', 'zhongguoren'],
            ['dui4 bu5 qi3', 'duibuqi'],
            ['asdf1234 asd3', 'asdfasd']
        ];
    }
}
