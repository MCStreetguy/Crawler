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
use MCStreetguy\Crawler\Queue\CrawlQueueInterface;
use MCStreetguy\Crawler\Queue\CrawlQueue;

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

    /** @var CrawlQueueInterface The crawl queue */
    protected $queue;

    /**
     * Constructs a new instance.
     *
     * @param CrawlConfigurationInterface $config The configuration object to use for the crawler
     * @param CrawlQueueInterface $queue The crawl queue to use for the crawler
     * @return void
     */
    public function __construct(
        CrawlConfigurationInterface $config = null,
        CrawlQueueInterface $queue = null
    ) {
        if ($config === null) {
            $config = new DefaultCrawlConfiguration;
        }
        
        if ($queue === null) {
            $queue = new CrawlQueue;
        }
        
        $this->configuration = $config;
        $this->queue = $queue;
    }
}
