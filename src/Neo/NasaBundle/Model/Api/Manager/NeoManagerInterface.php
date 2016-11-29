<?php
namespace Neo\NasaBundle\Model\Api\Manager;

use Neo\NasaBundle\Model\Api\Document\NeoInterface;

/**
 * Neo Manager
 */
interface NeoManagerInterface
{
    /**
     * Save
     *
     * @param NeoInterface $neo
     *
     * @return void
     */
    public function save(NeoInterface $neo);
}
