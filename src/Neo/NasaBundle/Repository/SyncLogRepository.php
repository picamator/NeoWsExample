<?php
namespace Neo\NasaBundle\Repository;

use Doctrine\MongoDB\Query\Query;
use Neo\NasaBundle\Model\Api\Repository\SyncLogRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * NASA synchronization log repository
 */
class SyncLogRepository extends DocumentRepository implements SyncLogRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getLastSync()
    {
        /** @var Query $query */
        $query = $this->dm->createQueryBuilder('NeoNasaBundle:SyncLog')
            ->sort('id', 'DESC')
            ->limit(1)
            ->readOnly()
            ->getQuery();

        return $query->getSingleResult();
    }
}
