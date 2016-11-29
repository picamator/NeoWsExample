<?php
namespace Neo\NasaBundle\Model\Response;

use Neo\NasaBundle\Model\Api\Response\DefaultResponseInterface;

/**
 * Default value object
 *
 * @codeCoverageIgnore
 */
class DefaultResponse implements DefaultResponseInterface
{
    /**
     * @var string
     */
    private $hello;

    /**
     * @inheritDoc
     */
    public function getHello()
    {
        return $this->hello;
    }

    /**
     * @inheritDoc
     */
    public function setHello($hello)
    {
        $this->hello = $hello;
    }
}
