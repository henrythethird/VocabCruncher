<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Sentence;
use AppBundle\Service\ExplainService;
use AppBundle\Service\SentenceCompileService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class SentenceCompileServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Sentence already compiled
     */
    public function testThrowsExceptionOnNonEmptyList()
    {
        $sentenceMock = $this->getMockBuilder(Sentence::class)
            ->setMethods(["getIndexes"])
            ->getMock();

        $sentenceMock->method("getIndexes")
            ->willReturn(new ArrayCollection([1]));

        $emMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $explainMock = $this->getMockBuilder(ExplainService::class)
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var Sentence $sentenceMock
         * @var ExplainService $explainMock
         * @var EntityManager $emMock
         */
        $sentenceUtil = new SentenceCompileService($emMock, $explainMock);
        $sentenceUtil->compile($sentenceMock);
    }
}
