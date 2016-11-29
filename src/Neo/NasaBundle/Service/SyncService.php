<?php
namespace Neo\NasaBundle\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Neo\NasaBundle\Model\Api\Document\NeoInterface;
use Neo\NasaBundle\Model\Api\Document\SyncLogInterface;
use Neo\NasaBundle\Model\Api\Manager\NeoManagerInterface;
use Neo\NasaBundle\Model\Api\Manager\SyncLogManagerInterface;
use Neo\NasaBundle\Model\Api\Nasa\CrawlerInterface;

/**
 * Sync Service
 */
class SyncService
{
    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var CrawlerInterface
     */
    private $crawler;

    /**
     * @var NeoManagerInterface
     */
    private $neoManager;

    /**
     * @var SyncLogManagerInterface
     */
    private $syncLogManager;

    /**
     * @param DocumentManager $dm
     * @param CrawlerInterface $crawler
     * @param NeoManagerInterface $neoManager
     * @param SyncLogManagerInterface $syncLogManager
     */
    public function __construct(
        DocumentManager $dm,
        CrawlerInterface $crawler,
        NeoManagerInterface $neoManager,
        SyncLogManagerInterface $syncLogManager
    ) {
        $this->dm = $dm;
        $this->crawler = $crawler;
        $this->neoManager = $neoManager;
        $this->syncLogManager = $syncLogManager;
    }

    /**
     * Synchronize Neo document with NASA api
     *
     * @param SyncLogInterface $syncLog
     *
     * @return int number ob sync neo, zero means today was already done sync
     */
    public function syncNeo(SyncLogInterface $syncLog)
    {
        // get neo collection
        $collection = $this->crawler->findNeoList($syncLog->getStartDate(), $syncLog->getEndDate());
        if ($collection->count() === 0) {
            return 0;
        }

        // save
        /** @var NeoInterface $item */
        foreach ($collection as $item) {
            $this->neoManager->save($item);
        }
        $this->syncLogManager->save($syncLog);
        $this->dm->flush();

        return $collection->count();
    }
}
