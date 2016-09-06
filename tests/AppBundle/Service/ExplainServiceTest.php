<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\ExplainService;

class ExplainServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExplainService
     */
    private $service;

    protected function setUp()
    {
        $mockService = $this->getMockBuilder(ExplainService::class)
            ->disableOriginalConstructor()
            ->setMethods(["query"])
            ->getMock()
        ;

        $mockService
            ->method("query")
            ->willReturnMap([
                ['Hello', 'Hello'],
                ['my', 'my'],
                ['name', 'name'],
                ['is', 'is'],
                ['foobar', 'foobar']
            ])
        ;

        $this->service = $mockService;
    }

    /**
     * @dataProvider explainProvider
     * @param string $sentence
     * @param array|null $expected
     */
    public function testExplainWorksCorrectly($sentence, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->service
                ->explain($sentence)
        );
    }

    public function explainProvider()
    {
        return [
            ["", []],
            ["Hello", ["Hello"]],
            ["NotInBook", [null, null, null, null, null, null, null, null, null]],
            ["nadHello", [null, null, null, "Hello"]],
            ["Hello my name is foobar", [
                0 => "Hello",
                5 => null,
                6 => "my",
                8 => null,
                9 => "name",
                13 => null,
                14 => "is",
                16 => null,
                17 => "foobar"
            ]]
        ];
    }

    /**
     * @dataProvider backtrackProvider
     * @param $string
     * @param $expectedLength
     */
    public function testBacktrackQuery($string, $expectedLength, $expectedArr)
    {
        $returnArrayStub = [];
        $this->assertEquals(
            $expectedLength,
            $this->service
                ->backtrackQuery($string, 0, $returnArrayStub)
        );
        $this->assertEquals($expectedArr, $returnArrayStub);
    }

    public function backtrackProvider()
    {
        return [
            ["Hello World", 5, [0 => "Hello"]],
            ["asdf", 1, [0 => null]],
            ["foobar", 6, [0 => "foobar"]]
        ];
    }
}
