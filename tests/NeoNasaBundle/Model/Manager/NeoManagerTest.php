<?php
namespace Neo\NasaBundle\Tests\Model\Manager;

use Neo\NasaBundle\Model\Manager\NeoManager;
use Neo\NasaBundle\Tests\BaseTest;

class NeoManagerTest extends BaseTest
{
    /**
     * @var NeoManager
     */
    private $manager;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager | \PHPUnit_Framework_MockObject_MockObject
     */
    private $documentManagerMock;

    /**
     * @var \Neo\NasaBundle\Model\Api\Document\NeoInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $neoMock;

    protected function setUp()
    {
        parent::setUp();

        $this->documentManagerMock = $this->getMockBuilder('Doctrine\ODM\MongoDB\DocumentManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->neoMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Document\NeoInterface')
            ->getMock();

        $this->manager = new NeoManager($this->documentManagerMock);
    }

    public function testSave()
    {
        // document manager mock
        $this->documentManagerMock->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($this->neoMock));

        $this->manager->save($this->neoMock);
    }
}
