<?php
namespace Neo\NasaBundle\Tests\Model\Manager;

use Neo\NasaBundle\Model\Manager\SyncLogManager;
use Neo\NasaBundle\Tests\BaseTest;

class SyncLogManagerTest extends BaseTest
{
    /**
     * @var SyncLogManager
     */
    private $manager;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager | \PHPUnit_Framework_MockObject_MockObject
     */
    private $documentManagerMock;

    /**
     * @var \Neo\NasaBundle\Model\Api\Document\SyncLogInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $syncLogMock;

    protected function setUp()
    {
        parent::setUp();

        $this->documentManagerMock = $this->getMockBuilder('Doctrine\ODM\MongoDB\DocumentManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->syncLogMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Document\SyncLogInterface')
            ->getMock();

        $this->manager = new SyncLogManager($this->documentManagerMock);
    }

    public function testSave()
    {
        // document manager mock
        $this->documentManagerMock->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($this->syncLogMock));

        $this->manager->save($this->syncLogMock);
    }
}
