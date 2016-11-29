<?php
namespace Neo\NasaBundle\Model\Api\Document;

/**
 * Neo document
 */
interface NeoInterface
{
    /**
     * Get id
     *
     * @return string
     */
    public function getId();

    /**
     * Get date
     *
     * @return string
     */
    public function getDate();

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return $this
     */
    public function setDate(\DateTime $date);

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference();

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return $this
     */
    public function setReference($reference);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * Get average speed over all close approach date in km/h
     *
     * @return string
     */
    public function getSpeed();

    /**
     * Set speed, km/h
     *
     * @param string $speed
     *
     * @return $this
     */
    public function setSpeed($speed);

    /**
     * Get isHazardous
     *
     * @return bool
     */
    public function getIsHazardous();

    /**
     * Set isHazardous
     *
     * @param bool $isHazardous
     *
     * @return $this
     */
    public function setIsHazardous($isHazardous);
}
