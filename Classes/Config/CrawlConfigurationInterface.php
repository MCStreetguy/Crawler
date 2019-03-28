<?php

/**
 * This file is part of the mcstreetguy/crawler package.
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @version    SVN: $Id$
 */

namespace MCStreetguy\Crawler\Config;

/**
 * Specifies a standard for crawl-configuration-classes.
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
interface CrawlConfigurationInterface
{
    /**
     * Get the maximum crawl count.
     *
     * Get the maximum number of crawl requests that may be performed.
     *
     * @return int
     */
    public function getMaximumCrawlCount(): int;

    /**
     * Get the maximum response size.
     *
     * Get the maximum size in bytes that is allowed for crawl-responses.
     *
     * @return int
     */
    public function getMaximumResponseSize(): float;

    /**
     * Get the maximum depth.
     *
     * Get the maximum depth of pages that the crawler may follow.
     *
     * @return int
     */
    public function getMaximumDepth(): int;

    /**
     * Get the delay in milliseconds before each request.
     *
     * Get the delay in milliseconds before each request.
     *
     * @return float
     */
    public function getRequestDelay(): float;

    /**
     * Build up the guzzle request options array.
     *
     * Build up the guzzle request options array.
     *
     * @return array
     */
    public function buildGuzzleRequestOptions(): array;
}
