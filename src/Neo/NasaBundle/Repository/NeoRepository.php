<?php
namespace Neo\NasaBundle\Repository;

use Doctrine\MongoDB\Query\Query;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Neo\NasaBundle\Model\Api\Repository\NeoRepositoryInterface;

/**
 * Neo repository
 */
class NeoRepository extends DocumentRepository implements NeoRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getHazardous()
    {
        /** @var Query $query */
        $query = $this->dm->createQueryBuilder('NeoNasaBundle:Neo')
            ->where("function() { return this.isHazardous == true; }")
            ->readOnly()
            ->getQuery();

        return $query->execute();
    }

    /**
     * @inheritDoc
     *
     * @todo find way to use aggregation https://docs.mongodb.com/manual/reference/operator/aggregation/group/
     */
    public function getFastest()
    {
        /** @var Query $query */
        $query = $this->dm->createQueryBuilder('NeoNasaBundle:Neo')
            ->sort('speed', 'DESC')
            ->limit(1)
            ->readOnly()
            ->getQuery();

        return $query->getSingleResult();
    }
}
