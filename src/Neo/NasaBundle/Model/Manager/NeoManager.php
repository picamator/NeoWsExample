<?php
namespace Neo\NasaBundle\Model\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;
use Neo\NasaBundle\Model\Api\Document\NeoInterface;
use Neo\NasaBundle\Model\Api\Manager\NeoManagerInterface;

/**
 * Neo Manager
 */
class NeoManager implements NeoManagerInterface
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
    public function save(NeoInterface $neo)
    {
        $this->dm->persist($neo);
    }
}
