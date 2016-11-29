<?php
namespace Neo\NasaBundle\Model\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;
use Neo\NasaBundle\Model\Api\Document\SyncLogInterface;
use Neo\NasaBundle\Model\Api\Manager\SyncLogManagerInterface;

/**
 * SyncLog Manager
 */
class SyncLogManager implements SyncLogManagerInterface
{
    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @param DocumentManager $dm
     */
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * @inheritDoc
     */
    public function save(SyncLogInterface $syncLog)
    {
        $this->dm->persist($syncLog);
    }
}
