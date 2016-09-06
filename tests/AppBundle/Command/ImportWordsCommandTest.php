<?php

namespace AppBundle\Command;

class ImportWordsCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parseProvider
     * @param $line
     * @param $expectedOutput
     */
    public function testParseWorksAsExpected($line, $expectedOutput)
    {
        $command = new ImportWordsCommand();
        $this->assertEquals($expectedOutput, $command->parse($line));
    }

    public function parseProvider()
    {
        return [
            [
                "aa bb [ss dd] /1/2/3/4/",
                [
                    'complex' => 'aa',
                    'simplified' => 'bb',
                    'pinyin' => 'ss dd',
                    'english' => [
                        '1',
                        '2',
                        '3',
                        '4',
                    ],
                ],
            ],
            [
                "#asdf asdf asdasdasd",
                false,
            ],
        ];
    }
}
