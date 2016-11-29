<?php
namespace Neo\NasaBundle\Tests\Model;

use Neo\NasaBundle\Service\HazardousService;
use Neo\NasaBundle\Tests\BaseTest;

class HazardousServiceTest extends BaseTest
{
    /**
     * @var HazardousService
     */
    private $service;

    /**
     * @var \Neo\NasaBundle\Model\Api\Repository\NeoRepositoryInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $repositoryMock;

    /**
     * @var \Doctrine\Common\Collections\Collection | \PHPUnit_Framework_MockObject_MockObject
     */
    private $collectionMock;

    protected function setUp()
    {
        parent::setUp();

        $this->repositoryMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Repository\NeoRepositoryInterface')
            ->getMock();

        $this->collectionMock = $this->getMockBuilder('Doctrine\Common\Collections\Collection')
            ->getMock();

        $this->service = new HazardousService($this->repositoryMock, $this->collectionMock);
    }

    public function testEmptyGetHazardous()
    {
        // repository mock
        $this->repositoryMock->expects($this->once())
            ->method('getHazardous');

        // never
        $this->collectionMock->expects($this->never())
            ->method('add');

        $this->service->getHazardous();
    }

    public function testGetHazardous()
    {
        $data = [1, 2, 3];

        // repository mock
        $this->repositoryMock->expects($this->once())
            ->method('getHazardous')
            ->willReturn($data);

        // collection mock
        $this->collectionMock->expects($this->exactly(count($data)))
            ->method('add');

        $this->service->getHazardous();
    }
}
