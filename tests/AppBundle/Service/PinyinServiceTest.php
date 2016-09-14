<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\PinyinService;

class PinyinServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PinyinService
     */
    private $util;

    protected function setUp()
    {
        $this->util = new PinyinService();
    }

    /**
     * @dataProvider numberPlainProvider
     * @param $numberedString
     * @param $expected
     */
    public function testConvertsCorrectlyFromNumberToPlain($numberedString, $expected)
    {
        $this->assertEquals($expected, $this->util->fromNumberToPlain($numberedString));
    }

    public function numberPlainProvider()
    {
        return [
            ['zhong1 guo3 ren2', 'zhongguoren'],
            ['dui4 bu5 qi3', 'duibuqi'],
            ['asdf1234 asd3', 'asdfasd']
        ];
    }

    /**
     * @dataProvider sampleSearchProvider
     * @param string $searchString
     * @param bool $expected
     */
    public function testRecognizesChineseCharsCorrectly($searchString, $expected)
    {
        $this->assertEquals($expected, $this->util->containsChinese($searchString));
    }

    public function sampleSearchProvider()
    {
        return [
            ['hello there is no chinese here!', false],
            ['你住在哪儿？我住在那儿！', true],
            ['爸爸 is chinese for father', true]
        ];
    }

    /**
     * @dataProvider sampleFilterSearchProvider
     * @param string $searchString
     * @param bool $expected
     */
    public function testSearchCorrectlyFiltered($searchString, $expected)
    {
        $this->assertEquals($expected, $this->util->filterChinese($searchString));
    }

    public function sampleFilterSearchProvider()
    {
        return [
            ['hello there is no chinese here!', ''],
            ['你住在哪儿？我住在那儿！', '你住在哪儿我住在那儿'],
            ['爸爸 is chinese for father', '爸爸']
        ];
    }
}
