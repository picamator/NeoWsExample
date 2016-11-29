<?php
namespace Neo\NasaBundle\Model\Nasa;

use Doctrine\Common\Collections\Collection;
use Neo\NasaBundle\Exception\CrawlerException;
use Neo\NasaBundle\Exception\InvalidArgumentException;
use Neo\NasaBundle\Model\Api\Builder\NeoFactoryInterface;
use Neo\NasaBundle\Model\Api\Nasa\CrawlerInterface;
use Picamator\NeoWsClient\Manager\Api\ManagerInterface;
use Picamator\NeoWsClient\Request\Api\Builder\FeedRequestFactoryInterface;
use Picamator\NeoWsClient\Response\Api\Data\ResponseInterface;
use Picamator\NeoWsClient\Model\Api\Data\NeoInterface as ClientNeoInterface;
use Picamator\NeoWsClient\Exception\InvalidArgumentException as ClientInvalidArgumentException;
use Picamator\NeoWsClient\Exception\HttpClientException as ClientHttpClientException;
use Picamator\NeoWsClient\Exception\ManagerException as ClientManagerException;

/**
 * NASA Crawler
 */
class Crawler implements CrawlerInterface
{
    /**
     * @var int
     */
    private static $speedPrecision = 10;

    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * @var FeedRequestFactoryInterface
     */
    private $feedRequestFactory;

    /**
     * @var NeoFactoryInterface
     */
    private $neoFactory;

    /**
     * @var Collection
     */
    private $collection;

    public function __construct(
        ManagerInterface $manager,
        FeedRequestFactoryInterface $feedRequestFactory,
        NeoFactoryInterface $neoFactory,
        Collection $collection
    ) {
        $this->manager = $manager;
        $this->feedRequestFactory = $feedRequestFactory;
        $this->neoFactory = $neoFactory;
        $this->collection = $collection;
    }

    /**
     * @inheritDoc
     */
    public function findNeoList(\DateTime $startDate, \DateTime $endDate)
    {
        try {
            $request = $this->feedRequestFactory->create($startDate->format('Y-m-d'), $endDate->format('Y-m-d'), ['detailed' => false]);
            $response = $this->manager->find($request);
            $this->addToCollection($response);
        } catch (ClientInvalidArgumentException $e) {
            throw new InvalidArgumentException('Proceed 3-rd party exception', 0, $e);
        } catch (ClientHttpClientException $e) {
            throw new CrawlerException('Proceed 3-rd party exception', 0, $e);
        } catch(ClientManagerException $e) {
            throw new CrawlerException('Proceed 3-rd party exception', 0, $e);
        }

        return $this->collection;
    }

    /**
     * Add To Collection
     *
     * @param ResponseInterface $response
     *
     * @return void
     *
     * @throw CrawlerException
     */
    private function addToCollection(ResponseInterface $response)
    {
        if ($response->getCode() !== 200) {
            throw new CrawlerException(
                sprintf('Invalid response code "%s". Error message "%s"', $response->getCode(), $response->getData()->scalar)
            );
        }

        /** @var \Picamator\NeoWsClient\Model\Api\Data\FeedInterface $data */
        $data = $response->getData();

        /** @var \Picamator\NeoWsClient\Model\Api\Data\Component\NeoDateInterface $item */
        foreach($data->getNeoDateList() as $item) {
            /** @var ClientNeoInterface $neoItem */
            foreach($item->getNeoList() as $neoItem) {
                $neoDocument = $this->neoFactory->create();
                $neoDocument
                    ->setDate($item->getDate())
                    ->setIsHazardous($neoItem->hasPotentiallyHazardousAsteroid())
                    ->setReference($neoItem->getNeoReferenceId())
                    ->setName($neoItem->getName())
                    ->setSpeed($this->getAverageSpeed($neoItem));

                $this->collection->add($neoDocument);
            }
        }
    }

    /**
     * Get average speed
     *
     * @param ClientNeoInterface $neoItem
     *
     * @return string
     */
    private function getAverageSpeed(ClientNeoInterface $neoItem)
    {
        $approachData = $neoItem->getCloseApproachData();
        if (is_null($approachData) || $approachData->count() === 0) {
            return '0';
        }

        $sumSpeed = '0';
        /** @var \Picamator\NeoWsClient\Model\Api\Data\Component\CloseApproachInterface $item */
        foreach ($approachData as $item) {
            $sumSpeed = bcadd($sumSpeed, $item->getRelativeVelocity()->getKilometersPerHour(), self::$speedPrecision);
        }

        return bcdiv($sumSpeed,  (string) $approachData->count(), self::$speedPrecision);
    }
}
