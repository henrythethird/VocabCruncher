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

    /**
     * No matter if chinese input is preferred (as an argument to the search
     * method) The search utility should always prefer chinese in case of
     * chinese characters in the string.
     *
     * @dataProvider sampleSearchStringProvider
     * @param $searchString
     */
    public function notestAltersToChineseIfCharsPresent($searchString)
    {
        $repoMock = $this->getMockBuilder(WordRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(["dictionarySearchChinese", "dictionarySearchEnglish"])
            ->getMock();

        $repoMock->method("dictionarySearchChinese")
            ->willReturn('爸爸');
        $repoMock->method("dictionarySearchEnglish")
            ->willThrowException(new \Exception());

        /**
         * @var WordRepository $repoMock
         */
        $util = new SearchUtil($repoMock);

        $this->assertEquals('爸爸', $util->search($searchString, true));
        $this->assertEquals('爸爸', $util->search($searchString, false));
    }

    public function sampleSearchStringProvider()
    {
        return [
            ['This string contains chinese at the very end 爸爸'],
            ['爸爸 the beginning is a chinese char'],
            ['In the 爸爸 middle']
        ];
    }
}
