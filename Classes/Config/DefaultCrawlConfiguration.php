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
 * @api
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class DefaultCrawlConfiguration implements CrawlConfigurationInterface
{
    /** @var int The maximum crawl count. */
    protected $maxCrawlCount = 0;

    /** @var int The maximum crawl depth. */
    protected $maxDepth = 0;

    /** @var float The maximum response size. */
    protected $maxResponseSize = 5.243e+6;

    /** @var float The delay of each request */
    protected $requestDelay = 0.0;

    /** @var int The timeout of each request */
    protected $requestTimeout = 60;

    /** @var bool If robots.txt is ignored */
    protected $ignoreRobots = false;

    /** @inheritDoc */
    public function getMaximumCrawlCount(): int
    {
        return $this->maxCrawlCount;
    }

    /** @inheritDoc */
    public function getMaximumDepth(): int
    {
        return $this->maxDepth;
    }

    /** @inheritDoc */
    public function getMaximumResponseSize(): float
    {
        return $this->maxResponseSize;
    }

    /** @inheritDoc */
    public function getRequestDelay(): float
    {
        return $this->requestDelay;
    }

    /** @inheritDoc */
    public function getRequestTimeout(): int
    {
        return $this->requestTimeout;
    }

    /** @inheritDoc */
    public function isRobotsTxtIgnored(): bool
    {
        return $this->ignoreRobots;
    }
}
