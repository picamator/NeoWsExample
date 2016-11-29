<?php
namespace Neo\NasaBundle\Model\Builder;

use Neo\NasaBundle\Model\Api\Builder\NeoFactoryInterface;
use Neo\NasaBundle\Model\Api\ObjectManagerInterface;

/**
 * Create Neo
 */
class NeoFactory implements NeoFactoryInterface
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var string
     */
    private $className;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param string $className
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        $className = 'Neo\NasaBundle\Document\Neo'
    ) {
        $this->objectManager = $objectManager;
        $this->className = $className;
    }

    /**
     * @inheritDoc
     */
    public function create()
    {
        return $this->objectManager->create($this->className);
    }
}
