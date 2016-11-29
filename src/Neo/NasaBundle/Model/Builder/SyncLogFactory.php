<?php
namespace Neo\NasaBundle\Model\Builder;

use Neo\NasaBundle\Model\Api\Builder\SyncLogFactoryInterface;
use Neo\NasaBundle\Model\Api\Document\SyncLogInterface;
use Neo\NasaBundle\Model\Api\ObjectManagerInterface;
use Neo\NasaBundle\Model\Api\Repository\SyncLogRepositoryInterface;
use Neo\NasaBundle\Exception\InvalidArgumentException;

/**
 * Create SyncLog
 *
 * @todo solve case when there are 3 past day data in database but it was asked get 5 days
 * @todo solve case when there are 3 past day data in database but it was asked get 5 days after 1 day gap
 */
class SyncLogFactory implements SyncLogFactoryInterface
{
    /**
     * @var int
     */
    private static $maxDayLimit = 7;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var SyncLogRepositoryInterface
     */
    private $repository;

    /**
     * @var string
     */
    private $className;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param SyncLogRepositoryInterface $repository
     * @param string $className
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        SyncLogRepositoryInterface $repository,
        $className = 'Neo\NasaBundle\Document\SyncLog'
    ) {
        $this->objectManager = $objectManager;
        $this->repository = $repository;
        $this->className = $className;
    }

    /**
     * @inheritDoc
     */
    public function create($day)
    {
        if ($day <= 0 || $day > self::$maxDayLimit || !is_int($day)) {
            throw new InvalidArgumentException(
                sprintf('Invalid day. Day "%s" has invalid data type or it is exceed max limit "%s".', $day, self::$maxDayLimit)
            );
        }

        // current day was taken into account
        $day --;

        $lastSync = $this->repository->getLastSync();
        $startDate = new \DateTime(sprintf('- %s Days', $day));
        $endDate = new \DateTime();

        // empty sync log
        if (is_null($lastSync)) {
            return $this->getSyncLog($startDate, $endDate);
        }

        // duplication run
        if ($lastSync->getEndDate()->format('Y-m-d') === $endDate->format('Y-m-d')) {
            return null;
        }

        // it's the right time for sync or it was an gap between sync
        $diffStart = intval($lastSync->getEndDate()->diff($startDate)->format('%R%a'));
        if ($diffStart >= $day) {
            return $this->getSyncLog($startDate, $endDate);
        }

        // overlap existing data with new ones
        return $this->getSyncLog($lastSync->getEndDate(), $endDate);
    }

    /**
     * Get SyncLog
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return SyncLogInterface
     */
    private function getSyncLog(\DateTime $startDate, \DateTime $endDate)
    {
        /** @var SyncLogInterface $syncLog */
        $syncLog = $this->objectManager->create($this->className);
        $syncLog->setStartDate($startDate)
            ->setEndDate($endDate);

        return $syncLog;
    }
}
