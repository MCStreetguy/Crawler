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

namespace MCStreetguy\Crawler;

use MCStreetguy\Crawler\Config\CrawlConfigurationInterface;
use MCStreetguy\Crawler\Config\DefaultCrawlConfiguration;
use Psr\Http\Message\UriInterface;

/**
 * The main class of the web-crawler.
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class Crawler
{
    /** @var UriInterface The target url to crawl. */
    protected $target;

    /** @var CrawlConfigurationInterface The crawler configuration object. */
    protected $configuration;

    /**
     * Constructs a new instance.
     *
     * @param CrawlConfigurationInterface $config The configuration object to use for the crawler
     * @return void
     */
    public function __construct(CrawlConfigurationInterface $config = null)
    {
        if ($config === null) {
            $config = new DefaultCrawlConfiguration;
        }

        $this->configuration = $config;
    }

    /**
     * Get the target url to crawl.
     *
     * @return UriInterface
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Get the crawler configuration object.
     *
     * @return CrawlConfigurationInterface
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
