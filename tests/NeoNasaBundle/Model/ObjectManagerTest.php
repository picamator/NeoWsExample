<?php
namespace Neo\NasaBundle\Tests\Model;

use Neo\NasaBundle\Model\ObjectManager;
use Neo\NasaBundle\Tests\BaseTest;

class ObjectManagerTest extends BaseTest
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        parent::setUp();

        $this->objectManager = new ObjectManager();
    }

    /**
     * @dataProvider providerCreate
     *
     * @param array $arguments
     */
    public function testCreate(array $arguments)
    {
        $className = '\DateTime';

        $actual = $this->objectManager->create($className, $arguments);
        $this->assertInstanceOf($className, $actual);
    }

    /**
     * @expectedException \Neo\NasaBundle\Exception\RuntimeException
     */
    public function testFailCreate()
    {
        $this->objectManager->create('Neo\NasaBundle\Model\ObjectManager', [1, 2]);
    }

    public function providerCreate()
    {
        return [
            [['now']],
            [[]]
        ];
    }
}
