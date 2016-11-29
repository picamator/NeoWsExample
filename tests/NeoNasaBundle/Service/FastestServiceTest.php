<?php
namespace Neo\NasaBundle\Tests\Model;

use Neo\NasaBundle\Service\FastestService;
use Neo\NasaBundle\Tests\BaseTest;

class FastestServiceTest extends BaseTest
{
    /**
     * @var FastestService
     */
    private $service;

    /**
     * @var \Neo\NasaBundle\Model\Api\Repository\NeoRepositoryInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $repositoryMock;

    protected function setUp()
    {
        parent::setUp();

        $this->repositoryMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Repository\NeoRepositoryInterface')
            ->getMock();

        $this->service = new FastestService($this->repositoryMock);
    }

    public function testGetFastest()
    {
        // repository mock
        $this->repositoryMock->expects($this->once())
            ->method('getFastest');

        $this->service->getFastest();
    }
}
