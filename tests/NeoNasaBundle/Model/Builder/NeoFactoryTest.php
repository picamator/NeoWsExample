<?php
namespace Neo\NasaBundle\Tests\Model\Builder;

use Neo\NasaBundle\Model\Builder\NeoFactory;
use Neo\NasaBundle\Tests\BaseTest;

class NeoFactoryTest extends BaseTest
{
    /**
     * @var NeoFactory
     */
    private $factory;

    /**
     * @var \Neo\NasaBundle\Model\Api\ObjectManagerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManagerMock;

    protected function setUp()
    {
        parent::setUp();

        $this->objectManagerMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\ObjectManagerInterface')
            ->getMock();

        $this->factory = new NeoFactory($this->objectManagerMock);
    }

    public function testCreate()
    {
        // object manager mock
        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with($this->equalTo('Neo\NasaBundle\Document\Neo'));

        $this->factory->create();
    }
}
