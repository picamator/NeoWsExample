<?php
namespace Neo\NasaBundle\Model\Api\Response;

/**
 * Default value object
 */
interface DefaultResponseInterface
{
    /**
     * Get hello
     *
     * @return string
     */
    public function getHello();

    /**
     * Set hello
     *
     * @param string $hello
     *
     * @return self
     */
    public function setHello($hello);
}
