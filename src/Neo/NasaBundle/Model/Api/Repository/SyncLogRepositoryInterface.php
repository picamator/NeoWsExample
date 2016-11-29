<?php
namespace Neo\NasaBundle\Model\Api\Repository;

use Neo\NasaBundle\Model\Api\Document\SyncLogInterface;

/**
 * NASA synchronization log repository
 */
interface SyncLogRepositoryInterface
{
    /**
     * Get last nasa sync log
     *
     * @return SyncLogInterface | null
     */
    public function getLastSync();
}
