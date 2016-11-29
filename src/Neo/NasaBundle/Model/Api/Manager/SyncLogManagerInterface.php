<?php
namespace Neo\NasaBundle\Model\Api\Manager;

use Neo\NasaBundle\Model\Api\Document\NeoInterface;
use Neo\NasaBundle\Model\Api\Document\SyncLogInterface;

/**
 * SyncLog Manager
 */
interface SyncLogManagerInterface
{
    /**
     * Save
     *
     * @param SyncLogInterface $syncLog
     *
     * @return void
     */
    public function save(SyncLogInterface $syncLog);
}
