<?php
namespace Neo\NasaBundle\Service;

use Neo\NasaBundle\Model\Api\Repository\NeoRepositoryInterface;
use Picamator\NeoWsClient\Model\Api\Data\NeoInterface;

/**
 * Fastest Service
 */
class FastestService
{
    /**
     * @var NeoRepositoryInterface
     */
    private $repository;

    /**
     * @param NeoRepositoryInterface $repository
     */
    public function __construct(NeoRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Get fastest
     *
     * @return NeoInterface | null
     */
    public function getFastest()
    {
        return $this->repository->getFastest();
    }
}
