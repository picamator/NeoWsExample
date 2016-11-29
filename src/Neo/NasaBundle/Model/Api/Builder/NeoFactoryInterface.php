<?php
namespace Neo\NasaBundle\Model\Api\Builder;

use Neo\NasaBundle\Model\Api\Document\NeoInterface;

/**
 * Create Neo
 */
interface NeoFactoryInterface
{
    /**
     * Create
     *
     * @return NeoInterface
     */
    public function create();
}
