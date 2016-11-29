<?php
namespace Neo\NasaBundle\Model\Api\Nasa;

use Doctrine\Common\Collections\Collection;
use Neo\NasaBundle\Exception\CrawlerException;

/**
 * NASA Crawler
 */
interface CrawlerInterface
{
    /**
     * Find neo list
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return Collection
     *
     * @throws CrawlerException
     * @throws InvalidArgumentException
     */
    public function findNeoList(\DateTime $startDate, \DateTime $endDate);
}
