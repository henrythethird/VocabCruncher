<?php

namespace AppBundle\Command;

class ImportSentencesCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parseProvider
     * @param $line
     * @param $expected
     */
    public function testParsedCorrectly($line, $expected)
    {
        $command = new ImportSentencesCommand();
        $this->assertEquals($expected, $command->parse($line));
    }

    /**
     *
     */
    public function parseProvider()
    {
        return [
            [
                "asdf\tddhdf",
                [
                    'english' => 'ddhdf',
                    'mandarin' => 'asdf'
                ]
            ]
        ];
    }
}
