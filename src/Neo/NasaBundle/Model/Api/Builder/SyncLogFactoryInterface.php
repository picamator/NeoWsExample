<?php
namespace Neo\NasaBundle\Model\Api\Builder;

use Neo\NasaBundle\Exception\InvalidArgumentException;
use Neo\NasaBundle\Model\Api\Document\SyncLogInterface;

/**
 * Create SyncLog, for sync period
 */
interface SyncLogFactoryInterface
{
    /**
     * Create
     *
     * @param int $day started from now with in max period
     *
     * @return SyncLogInterface | null null means that can not build SyncLog instance because database has already had data
     *
     * @throws InvalidArgumentException
     */
    public function create($day);
}
