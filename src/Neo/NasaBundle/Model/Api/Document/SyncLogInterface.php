<?php
namespace Neo\NasaBundle\Model\Api\Document;

/**
 * NASA synchronization log document
 */
interface SyncLogInterface
{
    /**
     * Get id
     *
     * @return string
     */
    public function getId();

    /**
     * Get start date
     *
     * @return string
     */
    public function getStartDate();

    /**
     * Set start date
     *
     * @param \DateTime $startDate
     *
     * @return $this
     */
    public function setStartDate(\DateTime $startDate);

    /**
     * Get end date
     *
     * @return \DateTime
     */
    public function getEndDate();

    /**
     * Set start date
     *
     * @param \DateTime $endDate
     *
     * @return $this
     */
    public function setEndDate(\DateTime $endDate);
}
