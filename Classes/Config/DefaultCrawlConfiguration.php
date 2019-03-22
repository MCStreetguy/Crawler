<?php

/**
 * This file is part of the mcstreetguy/crawler package.
 *
 * @package    mcstreetguy/crawler
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @version    SVN: $Id$
 */

namespace MCStreetguy\Crawler\Config;

/**
 * The default crawl configuration class.
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class DefaultCrawlConfiguration implements CrawlConfigurationInterface
{
    /**
     * The maximum crawl count.
     * @var int
     */
    protected $maxCrawlCount = 0;

    /**
     * The maximum crawl depth.
     * @var int
     */
    protected $maxDepth = 0;

    /**
     * The maximum response size.
     * @var float
     */
    protected $maxResponseSize = 5.243e+6;

    /**
     * {@inheritDoc}
     */
    public function getMaximumCrawlCount(): int
    {
        return $this->maxCrawlCount;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaximumDepth(): int
    {
        return $this->maxDepth;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaximumResponseSize(): float
    {
        return $this->maxResponseSize;
    }
}
