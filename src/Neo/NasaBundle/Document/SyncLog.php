<?php
namespace Neo\NasaBundle\Document;

use Neo\NasaBundle\Model\Api\Document\SyncLogInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * NASA synchronization log document
 *
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Neo\NasaBundle\Repository\SyncLogRepository")
 *
 * @codeCoverageIgnore
 */
class SyncLog implements SyncLogInterface
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
    private $startDate;

    /**
     * @MongoDB\Field(type="date")
     *
     * @var \DateTime
     */
    public $endDate;

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
     * Get start date
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set start date
     *
     * @param \DateTime $startDate
     *
     * @return $this
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get end date
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set start date
     *
     * @param \DateTime $endDate
     *
     * @return $this
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }
}
