<?php

namespace Tests\AppBundle\Util;

use AppBundle\Repository\WordRepository;
use AppBundle\Util\SearchUtil;

class SearchUtilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider sequenceProvider
     */
    public function testSelectedCorrectly($chineseReturn, $englishReturn, $chineseFirst, $expected)
    {
        $repoMock = $this->getMockBuilder(WordRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(["dictionarySearchChinese", "dictionarySearchEnglish"])
            ->getMock();

        $repoMock->method("dictionarySearchChinese")
            ->willReturn($chineseReturn);
        $repoMock->method("dictionarySearchEnglish")
            ->willReturn($englishReturn);

        /**
         * @var WordRepository $repoMock
         */
        $util = new SearchUtil($repoMock);

        $this->assertEquals($expected, $util->search("test", $chineseFirst));
    }

    public function sequenceProvider()
    {
        return [
            [[1], null, true, [1]],
            [[], ["asdf"], true, ["asdf"]],
            [[1], [7], false, [7]],
            [[1], [], false, [1]]
        ];
    }
}
