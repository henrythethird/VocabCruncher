<?php

namespace Tests\AppBundle\Util;

use AppBundle\Entity\Sentence;
use AppBundle\Service\ExplainService;
use AppBundle\Util\SentenceCompileUtil;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class SentenceCompileUtilTest extends \PHPUnit_Framework_TestCase
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
        $sentenceUtil = new SentenceCompileUtil($sentenceMock, $explainMock, $emMock);
        $sentenceUtil->compile();
    }
}
