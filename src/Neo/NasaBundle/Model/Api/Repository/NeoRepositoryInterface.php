<?php
namespace Neo\NasaBundle\Model\Api\Repository;

use Picamator\NeoWsClient\Model\Api\Data\NeoInterface;

/**
 * Neo repository
 */
interface NeoRepositoryInterface
{
    /**
     * Get all hazardous asteroids
     *
     * @return array | null array of NeoInterface
     */
    public function getHazardous();

    /**
     * Get fastest asteroid
     *
     * @return NeoInterface
     */
    public function getFastest();
}
