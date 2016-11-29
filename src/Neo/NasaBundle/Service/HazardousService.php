<?php
namespace Neo\NasaBundle\Service;

use Doctrine\Common\Collections\Collection;
use Neo\NasaBundle\Model\Api\Repository\NeoRepositoryInterface;

/**
 * Hazardous Service
 */
class HazardousService
{
    /**
     * @var NeoRepositoryInterface
     */
    private $repository;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @param NeoRepositoryInterface $repository
     * @param Collection $collection
     */
    public function __construct(
        NeoRepositoryInterface $repository,
        Collection $collection
    ) {
        $this->repository = $repository;
        $this->collection = $collection;
    }

    /**
     * Get hazardous asteroids
     *
     * @return Collection
     */
    public function getHazardous()
    {
        $data = $this->repository->getHazardous();
        $data = $data ? : [];
        foreach($data as $item) {
            $this->collection->add($item);
        }

        return $this->collection;
    }
}
