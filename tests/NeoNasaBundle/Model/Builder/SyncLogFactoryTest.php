<?php
namespace Neo\NasaBundle\Tests\Model\Builder;

use Neo\NasaBundle\Model\Builder\SyncLogFactory;
use Neo\NasaBundle\Tests\BaseTest;

class SyncLogFactoryTest extends BaseTest
{
    /**
     * @var SyncLogFactory
     */
    private $factory;

    /**
     * @var \Neo\NasaBundle\Model\Api\ObjectManagerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManagerMock;

    /**
     * @var \Neo\NasaBundle\Model\Api\Repository\SyncLogRepositoryInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $repositoryMock;

    /**
     * @var \Neo\NasaBundle\Model\Api\Document\SyncLogInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $syncLogMock;

    protected function setUp()
    {
        parent::setUp();

        $this->objectManagerMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\ObjectManagerInterface')
            ->getMock();

        $this->repositoryMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Repository\SyncLogRepositoryInterface')
            ->getMock();

        $this->syncLogMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Document\SyncLogInterface')
            ->getMock();

        $this->factory = new SyncLogFactory($this->objectManagerMock, $this->repositoryMock);
    }

    /**
     * @dataProvider providerInvalidDayCreate
     *
     * @expectedException \Neo\NasaBundle\Exception\InvalidArgumentException
     *
     * @param int $day
     */
    public function testInvalidDayCreate($day)
    {
        // never
        $this->repositoryMock->expects($this->never())
            ->method('getLastSync');
        $this->objectManagerMock->expects($this->never())
            ->method('create');

        $this->factory->create(8);
    }

    public function testEmptySyncCreate()
    {
        // registry mock
        $this->repositoryMock->expects($this->once())
            ->method('getLastSync');

        // object manager mock
        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with($this->equalTo('Neo\NasaBundle\Document\SyncLog'))
            ->willReturn($this->syncLogMock);

        // sync log mock
        $this->syncLogMock->expects($this->once())
            ->method('setStartDate')
            ->willReturnSelf();

        $this->syncLogMock->expects($this->once())
            ->method('setEndDate')
            ->willReturnSelf();

        // never
        $this->syncLogMock->expects($this->never())
            ->method('getEndDate');

        $actual = $this->factory->create(3);
        $this->assertEquals($this->syncLogMock, $actual);
    }

    public function testDuplicationCreate()
    {
        $day = 3;
        $endDate = new \DateTime();

        // sync log mock
        $this->syncLogMock->expects($this->once())
            ->method('getEndDate')
            ->willReturn($endDate);

        // registry mock
        $this->repositoryMock->expects($this->once())
            ->method('getLastSync')
            ->willReturn($this->syncLogMock);

        // never
        $this->objectManagerMock->expects($this->never())
            ->method('create');
        $this->syncLogMock->expects($this->never())
            ->method('setStartDate');
        $this->syncLogMock->expects($this->never())
            ->method('setEndDate');

        $actual = $this->factory->create($day);
        $this->assertNull($actual);
    }

    public function testExactlyRightSyncCreate()
    {
        $day = 3;
        $endDate = new \DateTime(sprintf('- %s Days', 2 * $day));

        // sync log mock
        $this->syncLogMock->expects($this->exactly(2))
            ->method('getEndDate')
            ->willReturn($endDate);

        // registry mock
        $this->repositoryMock->expects($this->once())
            ->method('getLastSync')
            ->willReturn($this->syncLogMock);

        // object manager mock
        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with($this->equalTo('Neo\NasaBundle\Document\SyncLog'))
            ->willReturn($this->syncLogMock);

        // sync log mock
        $this->syncLogMock->expects($this->once())
            ->method('setStartDate')
            ->willReturnSelf();

        $this->syncLogMock->expects($this->once())
            ->method('setEndDate')
            ->willReturnSelf();

        $actual = $this->factory->create($day);
        $this->assertEquals($this->syncLogMock, $actual);
    }

    public function testOverlapCreate()
    {
        $day = 3;
        $endDate = new \DateTime(sprintf('- %s Days', 1));

        // sync log mock
        $this->syncLogMock->expects($this->exactly(3))
            ->method('getEndDate')
            ->willReturn($endDate);

        // registry mock
        $this->repositoryMock->expects($this->once())
            ->method('getLastSync')
            ->willReturn($this->syncLogMock);

        // object manager mock
        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with($this->equalTo('Neo\NasaBundle\Document\SyncLog'))
            ->willReturn($this->syncLogMock);

        // sync log mock
        $this->syncLogMock->expects($this->once())
            ->method('setStartDate')
            ->willReturnSelf();

        $this->syncLogMock->expects($this->once())
            ->method('setEndDate')
            ->willReturnSelf();

        $actual = $this->factory->create($day);
        $this->assertEquals($this->syncLogMock, $actual);
    }

    public function providerInvalidDayCreate()
    {
        return [
            [8],
            [-1],
            [0],
            [1.2],
            ['3'],
        ];
    }
}
