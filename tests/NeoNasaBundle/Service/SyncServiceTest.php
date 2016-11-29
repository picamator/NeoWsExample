<?php
namespace Neo\NasaBundle\Tests\Model;

use Neo\NasaBundle\Service\SyncService;
use Neo\NasaBundle\Tests\BaseTest;

class SyncServiceTest extends BaseTest
{
    /**
     * @var SyncService
     */
    private $service;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManage | \PHPUnit_Framework_MockObject_MockObject
     */
    private $dmMock;

    /**
     * @var \Neo\NasaBundle\Model\Api\Nasa\CrawlerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $crawlerMock;

    /**
     * @var \Neo\NasaBundle\Model\Api\Manager\NeoManagerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $neoManagerMock;

    /**
     * @var \Neo\NasaBundle\Model\Api\Manager\SyncLogManagerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $syncLogManagerMock;

    /**
     * @var \Doctrine\Common\Collections\Collection | \PHPUnit_Framework_MockObject_MockObject
     */
    private $collectionMock;

    /**
     * @var \Neo\NasaBundle\Model\Api\Document\SyncLogInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $syncLogMock;

    protected function setUp()
    {
        parent::setUp();

        $this->dmMock = $this->getMockBuilder('Doctrine\ODM\MongoDB\DocumentManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->crawlerMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Nasa\CrawlerInterface')
            ->getMock();

        $this->neoManagerMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Manager\NeoManagerInterface')
            ->getMock();

        $this->syncLogManagerMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Manager\SyncLogManagerInterface')
            ->getMock();

        $this->collectionMock = $this->getMockBuilder('Doctrine\Common\Collections\Collection')
            ->getMock();

        $this->syncLogMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Document\SyncLogInterface')
            ->getMock();

        $this->service = new SyncService(
            $this->dmMock,
            $this->crawlerMock,
            $this->neoManagerMock,
            $this->syncLogManagerMock
        );
    }

    public function testZeroSyncNeo()
    {
        $startDate = new \DateTime('2016-11-26');
        $endDate = new \DateTime('2016-11-28');

        // syncLog mock
        $this->syncLogMock->expects($this->once())
            ->method('getStartDate')
            ->willReturn($startDate);

        $this->syncLogMock->expects($this->once())
            ->method('getEndDate')
            ->willReturn($endDate);

        // crawler mock
        $this->crawlerMock->expects($this->once())
            ->method('findNeoList')
            ->with($this->equalTo($startDate), $this->equalTo($endDate))
            ->willReturn($this->collectionMock);

        // collection mock
        $this->collectionMock->expects($this->once())
            ->method('count')
            ->willReturn(0);

        // never
        $this->neoManagerMock->expects($this->never())
            ->method('save');
        $this->syncLogManagerMock->expects($this->never())
            ->method('save');
        $this->dmMock->expects($this->never())
            ->method('flush');

        $actual = $this->service->syncNeo($this->syncLogMock);
        $this->assertEquals(0, $actual);
    }

    public function testSyncNeo()
    {
        $startDate = new \DateTime('2016-11-26');
        $endDate = new \DateTime('2016-11-28');

        $count  = 1;

        // syncLog mock
        $this->syncLogMock->expects($this->once())
            ->method('getStartDate')
            ->willReturn($startDate);

        $this->syncLogMock->expects($this->once())
            ->method('getEndDate')
            ->willReturn($endDate);

        // crawler mock
        $this->crawlerMock->expects($this->once())
            ->method('findNeoList')
            ->with($this->equalTo($startDate), $this->equalTo($endDate))
            ->willReturn($this->collectionMock);

        // neo mock
        $neoMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Document\NeoInterface')
            ->getMock();

        $data[] = $neoMock;

        // collection mock
        $this->collectionMock->expects($this->exactly(2))
            ->method('count')
            ->willReturn($count);

        $this->collectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($data));

        // neo manager mock
        $this->neoManagerMock->expects($this->once())
            ->method('save')
            ->with($this->equalTo($neoMock));

        // sync log mock
        $this->syncLogManagerMock->expects($this->once())
            ->method('save')
            ->with($this->equalTo($this->syncLogMock));

        // dm mock
        $this->dmMock->expects($this->once())
            ->method('flush');

        $actual = $this->service->syncNeo($this->syncLogMock);
        $this->assertEquals($count, $actual);
    }
}
