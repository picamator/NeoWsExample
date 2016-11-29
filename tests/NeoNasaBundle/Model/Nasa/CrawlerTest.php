<?php
namespace Neo\NasaBundle\Tests\Nasa;

use Neo\NasaBundle\Model\Nasa\Crawler;
use Neo\NasaBundle\Tests\BaseTest;
use Picamator\NeoWsClient\Exception\InvalidArgumentException as ClientInvalidArgumentException;
use Picamator\NeoWsClient\Exception\HttpClientException as ClientHttpClientException;
use Picamator\NeoWsClient\Exception\ManagerException as ClientManagerException;

class CrawlerTest extends BaseTest
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var \Picamator\NeoWsClient\Manager\Api\ManagerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $managerMock;

    /**
     * @var \Picamator\NeoWsClient\Request\Api\Builder\FeedRequestFactoryInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $feedRequestFactoryMock;

    /**
     * @var \Neo\NasaBundle\Model\Api\Builder\NeoFactoryInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $neoFactoryMock;

    /**
     * @var \Doctrine\Common\Collections\Collection | \PHPUnit_Framework_MockObject_MockObject
     */
    private $collectionMock;

    /**
     * @var \Picamator\NeoWsClient\Response\Api\Data\ResponseInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $responseMock;

    /**
     * @var \Picamator\NeoWsClient\Request\Api\Data\FeedRequestInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    protected function setUp()
    {
        parent::setUp();

        $this->managerMock = $this->getMockBuilder('Picamator\NeoWsClient\Manager\Api\ManagerInterface')
            ->getMock();

        $this->feedRequestFactoryMock = $this->getMockBuilder('Picamator\NeoWsClient\Request\Api\Builder\FeedRequestFactoryInterface')
            ->getMock();

        $this->neoFactoryMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Builder\NeoFactoryInterface')
            ->getMock();

        $this->collectionMock = $this->getMockBuilder('Doctrine\Common\Collections\Collection')
            ->getMock();

        $this->responseMock = $this->getMockBuilder('Picamator\NeoWsClient\Response\Api\Data\ResponseInterface')
            ->getMock();

        $this->requestMock = $this->getMockBuilder('Picamator\NeoWsClient\Request\Api\Data\FeedRequestInterface')
            ->getMock();

        $this->crawler = new Crawler(
            $this->managerMock,
            $this->feedRequestFactoryMock,
            $this->neoFactoryMock,
            $this->collectionMock
        );
    }

    /**
     * @dataProvider providerFailFindNeo
     *
     * @expectedException \Neo\NasaBundle\Exception\CrawlerException
     *
     * @param \Exception $exception
     */
    public function testFail(\Exception $exception)
    {
        $startDate = new \DateTime('2016-11-26');
        $endDate = new \DateTime('2016-11-28');

        // request factory mock
        $this->feedRequestFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->requestMock);

        // manager mock
        $this->managerMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo($this->requestMock))
            ->willThrowException($exception);

        // never
        $this->responseMock->expects($this->never())
            ->method('getCode');
        $this->responseMock->expects($this->never())
            ->method('getData');
        $this->neoFactoryMock->expects($this->never())
            ->method('create');
        $this->collectionMock->expects($this->never())
            ->method('add');

        $this->crawler->findNeoList($startDate, $endDate);
    }

    /**
     * @expectedException \Neo\NasaBundle\Exception\InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $startDate = new \DateTime('2016-11-26');
        $endDate = new \DateTime('2016-11-28');

        // request factory mock
        $this->feedRequestFactoryMock->expects($this->once())
            ->method('create')
            ->willThrowException(new ClientInvalidArgumentException());
        // never
        $this->managerMock->expects($this->never())
            ->method('find');
        $this->responseMock->expects($this->never())
            ->method('getCode');
        $this->responseMock->expects($this->never())
            ->method('getData');
        $this->neoFactoryMock->expects($this->never())
            ->method('create');
        $this->collectionMock->expects($this->never())
            ->method('add');

        $this->crawler->findNeoList($startDate, $endDate);
    }

    /**
     * @expectedException \Neo\NasaBundle\Exception\CrawlerException
     */
    public function testInvalidResponseCode()
    {
        $startDate = new \DateTime('2016-11-26');
        $endDate = new \DateTime('2016-11-28');

        // request factory mock
        $this->feedRequestFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->requestMock);

        // manager mock
        $this->managerMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo($this->requestMock))
            ->willReturn($this->responseMock);

        // response mock
        $this->responseMock->expects($this->exactly(2))
            ->method('getCode')
            ->willReturn(404);

        $this->responseMock->expects($this->once())
            ->method('getData')
            ->willReturn((object)'error');

        // never
        $this->neoFactoryMock->expects($this->never())
            ->method('create');
        $this->collectionMock->expects($this->never())
            ->method('add');

        $this->crawler->findNeoList($startDate, $endDate);
    }

    public function testZeroSpeed()
    {
        $startDate = new \DateTime('2016-11-26');
        $endDate = new \DateTime('2016-11-28');

        $isHazardous = true;
        $referenceId = 'B612';
        $name = 'The Little Prince';
        $speed = '0';

        // request factory mock
        $this->feedRequestFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->requestMock);

        // manager mock
        $this->managerMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo($this->requestMock))
            ->willReturn($this->responseMock);

        // neo mock
        $neoMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\NeoInterface')
            ->getMock();

        $neoMock->expects($this->once())
            ->method('hasPotentiallyHazardousAsteroid')
            ->willReturn($isHazardous);

        $neoMock->expects($this->once())
            ->method('getNeoReferenceId')
            ->willReturn($referenceId);

        $neoMock->expects($this->once())
            ->method('getName')
            ->willReturn($name);

        $neoMock->expects($this->once())
            ->method('getCloseApproachData');

        $neoListData[] = $neoMock;

        // neo list mock
        $neoListMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\Component\CollectionInterface')
            ->getMock();

        $neoListMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($neoListData));

        // neo data mock
        $neoDataMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\Component\NeoDateInterface')
            ->getMock();

        $neoDataMock->expects($this->once())
            ->method('getDate')
            ->willReturn($startDate);

        $neoDataMock->expects($this->once())
            ->method('getNeoList')
            ->willReturn($neoListMock);

        $neoData[] = $neoDataMock;

        // neo data list mock
        $neoDataListMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\Component\CollectionInterface')
            ->getMock();

        $neoDataListMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($neoData));

        // feed mock
        $feedMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\FeedInterface')
            ->getMock();

        $feedMock->expects($this->once())
            ->method('getNeoDateList')
            ->willReturn($neoDataListMock);

        // response mock
        $this->responseMock->expects($this->once())
            ->method('getCode')
            ->willReturn(200);

        $this->responseMock->expects($this->once())
            ->method('getData')
            ->willReturn($feedMock);

        // neo document mock
        $neoDocumentMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Document\NeoInterface')
            ->getMock();

        $neoDocumentMock->expects($this->once())
            ->method('setDate')
            ->with($this->equalTo($startDate))
            ->willReturnSelf();

        $neoDocumentMock->expects($this->once())
            ->method('setIsHazardous')
            ->with($this->equalTo($isHazardous))
            ->willReturnSelf();

        $neoDocumentMock->expects($this->once())
            ->method('setReference')
            ->with($this->equalTo($referenceId))
            ->willReturnSelf();

        $neoDocumentMock->expects($this->once())
            ->method('setName')
            ->with($this->equalTo($name))
            ->willReturnSelf();

        $neoDocumentMock->expects($this->once())
            ->method('setSpeed')
            ->with($this->equalTo($speed))
            ->willReturnSelf();

        // neo factory mock
        $this->neoFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($neoDocumentMock);

        // collection mock
        $this->collectionMock->expects($this->once())
            ->method('add')
            ->with($this->equalTo($neoDocumentMock));

        $this->crawler->findNeoList($startDate, $endDate);
    }

    public function testAverageSpeed()
    {
        $startDate = new \DateTime('2016-11-26');
        $endDate = new \DateTime('2016-11-28');

        $isHazardous = true;
        $referenceId = 'B612';
        $name = 'The Little Prince';
        $speed = '101271.1261468813';

        // request factory mock
        $this->feedRequestFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->requestMock);

        // manager mock
        $this->managerMock->expects($this->once())
            ->method('find')
            ->with($this->equalTo($this->requestMock))
            ->willReturn($this->responseMock);

        // velocity mock
        $velocityMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\Primitive\VelocityInterface')
            ->getMock();

        $velocityMock->expects($this->once())
            ->method('getKilometersPerHour')
            ->willReturn($speed);

        // close approach data mock
        $closeApproachItemMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\Component\CloseApproachInterface')
            ->getMock();

        $closeApproachItemMock->expects($this->once())
            ->method('getRelativeVelocity')
            ->willReturn($velocityMock);

        $approachData[] = $closeApproachItemMock;

        // close approach data list mock
        $closeApproachDataMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\Component\CollectionInterface')
            ->getMock();

        $closeApproachDataMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($approachData));

        $closeApproachDataMock->expects($this->exactly(2))
            ->method('count')
            ->willReturn(1);

        // neo mock
        $neoMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\NeoInterface')
            ->getMock();

        $neoMock->expects($this->once())
            ->method('hasPotentiallyHazardousAsteroid')
            ->willReturn($isHazardous);

        $neoMock->expects($this->once())
            ->method('getNeoReferenceId')
            ->willReturn($referenceId);

        $neoMock->expects($this->once())
            ->method('getName')
            ->willReturn($name);

        $neoMock->expects($this->once())
            ->method('getCloseApproachData')
            ->willReturn($closeApproachDataMock);

        $neoListData[] = $neoMock;

        // neo list mock
        $neoListMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\Component\CollectionInterface')
            ->getMock();

        $neoListMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($neoListData));

        // neo data mock
        $neoDataMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\Component\NeoDateInterface')
            ->getMock();

        $neoDataMock->expects($this->once())
            ->method('getDate')
            ->willReturn($startDate);

        $neoDataMock->expects($this->once())
            ->method('getNeoList')
            ->willReturn($neoListMock);

        $neoData[] = $neoDataMock;

        // neo data list mock
        $neoDataListMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\Component\CollectionInterface')
            ->getMock();

        $neoDataListMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($neoData));

        // feed mock
        $feedMock = $this->getMockBuilder('Picamator\NeoWsClient\Model\Api\Data\FeedInterface')
            ->getMock();

        $feedMock->expects($this->once())
            ->method('getNeoDateList')
            ->willReturn($neoDataListMock);

        // response mock
        $this->responseMock->expects($this->once())
            ->method('getCode')
            ->willReturn(200);

        $this->responseMock->expects($this->once())
            ->method('getData')
            ->willReturn($feedMock);

        // neo document mock
        $neoDocumentMock = $this->getMockBuilder('Neo\NasaBundle\Model\Api\Document\NeoInterface')
            ->getMock();

        $neoDocumentMock->expects($this->once())
            ->method('setDate')
            ->with($this->equalTo($startDate))
            ->willReturnSelf();

        $neoDocumentMock->expects($this->once())
            ->method('setIsHazardous')
            ->with($this->equalTo($isHazardous))
            ->willReturnSelf();

        $neoDocumentMock->expects($this->once())
            ->method('setReference')
            ->with($this->equalTo($referenceId))
            ->willReturnSelf();

        $neoDocumentMock->expects($this->once())
            ->method('setName')
            ->with($this->equalTo($name))
            ->willReturnSelf();

        $neoDocumentMock->expects($this->once())
            ->method('setSpeed')
            ->with($this->equalTo($speed))
            ->willReturnSelf();

        // neo factory mock
        $this->neoFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($neoDocumentMock);

        // collection mock
        $this->collectionMock->expects($this->once())
            ->method('add')
            ->with($this->equalTo($neoDocumentMock));

        $this->crawler->findNeoList($startDate, $endDate);
    }

    public function providerFailFindNeo()
    {
        return [
            [new ClientHttpClientException()],
            [new ClientManagerException()],
        ];
    }
}
