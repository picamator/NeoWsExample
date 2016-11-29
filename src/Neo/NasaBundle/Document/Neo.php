<?php
namespace Neo\NasaBundle\Document;

use Neo\NasaBundle\Model\Api\Document\NeoInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Neo document
 *
 * @MongoDB\Document
 * @MongoDB\UniqueIndex(keys={"reference"="asc", "date"="asc"})
 *
 * @codeCoverageIgnore
 */
class Neo implements NeoInterface
{
    /**
     * @MongoDB\Id
     *
     * @return string
     */
    private $id;

    /**
     * @MongoDB\Field(type="date")
     *
     * @var \DateTime
     */
    private $date;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    private $reference;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    private $name;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    private $speed;

    /**
     * @MongoDB\Field(type="boolean")
     *
     * @var bool
     */
    private $isHazardous;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return $this
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get average speed over all close approach date in km/h
     *
     * @return string
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * Set speed, km/h
     *
     * @param string $speed
     *
     * @return $this
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * Get isHazardous
     *
     * @return bool
     */
    public function getIsHazardous()
    {
        return $this->isHazardous;
    }

    /**
     * Set isHazardous
     *
     * @param bool $isHazardous
     *
     * @return $this
     */
    public function setIsHazardous($isHazardous)
    {
        $this->isHazardous = $isHazardous;

        return $this;
    }
}
