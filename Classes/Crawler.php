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
use GuzzleHttp\Psr7\Uri;
use Webmozart\Assert\Assert;
use MCStreetguy\Crawler\Processing\ProcessorInterface;
use MCStreetguy\Crawler\Processing\Validator\ValidatorInterface;
use GuzzleHttp\Client;
use MCStreetguy\Crawler\Result\CrawlResult;
use MCStreetguy\Crawler\Result\ResultSet;

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

    /** @var Seeker The link-seeker */
    protected $seeker;

    /** @var ProcessorInterface[] The registered processors */
    protected $processors;
    
    /** @var ValidatorInterface[] The registered validators */
    protected $validators;

    /** @var Client The http client */
    protected $client;

    /**
     * Constructs a new instance.
     *
     * @param CrawlConfigurationInterface|null $config The configuration object to use for the crawler
     * @param CrawlQueueInterface|null $queue The crawl queue to use for the crawler
     * @param ProcessorInterface[] $processors The processors to use for the crawl-results
     * @param ValidatorInterface[] $validators The validators to use for the crawler
     * @return void
     * @throws \InvalidArgumentException
     */
    public function __construct(
        CrawlConfigurationInterface $config = null,
        CrawlQueueInterface $queue = null,
        array $processors = [],
        array $validators = []
    ) {
        if ($config === null) {
            $config = new DefaultCrawlConfiguration;
        }
        $this->configuration = $config;
        
        if ($queue === null) {
            $queue = new CrawlQueue;
        }
        $this->queue = $queue;

        if (!empty($processors)) {
            Assert::allIsInstanceOf($processors, ProcessorInterface::class);
        }
        $this->processors = $processors;

        if (!empty($validators)) {
            Assert::allIsInstanceOf($validators, ValidatorInterface::class);
        }
        $this->validators = $validators;

        $this->seeker = new Seeker();
        $this->client = new Client();
    }

    /**
     * Execute the crawler for the given target.
     *
     * @param string|UriInterface $target The target to crawl
     * @return void
     * @throws \InvalidArgumentException
     */
    public function execute($target)
    {
        if (is_string($target)) {
            $target = new Uri($target);
        } elseif (! $target instanceof UriInterface) {
            $type = gettype($target);

            throw new \InvalidArgumentException(
                "\$target has to be a string or an instance of UriInterface, $type given!",
                1553768163007
            );
        }

        $this->target = $target;
        $this->queue->add($target);

        $results = [];
        $current = $this->queue->getNext();

        if ($current === null) {
            throw new \RuntimeException('Something went wrong while starting the crawling process!', 1553774441674);
        }

        do {
            $response = $this->client->request('GET', $current, $this->configuration->buildGuzzleRequestOptions());
            
            $furtherLinks = $this->seeker->browse($current, $response);
            $uniqueLinks = array_unique($furtherLinks, SORT_NATURAL);

            $result = new CrawlResult($current, $response, $furtherLinks);
            $this->queue->finish($current);

            $results[] = $result;
            $current = $this->queue->getNext();
        } while ($current !== null);

        $resultSet = new ResultSet($results);
    }

    /**
     * Get the base target uri of the crawler.
     *
     * @return UriInterface|null
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Get the configuration object of the crawler.
     *
     * @return CrawlConfigurationInterface
     */
    public function getConfiguration() : CrawlConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * Set the configuration object of the crawler.
     *
     * @param CrawlConfigurationInterface $config
     * @return void
     */
    public function setConfiguration(CrawlConfigurationInterface $config)
    {
        $this->configuration = $config;
    }

    /**
     * Get the crawl queue.
     *
     * @return CrawlQueueInterface
     */
    public function getCrawlQueue() : CrawlQueueInterface
    {
        return $this->queue;
    }
    
    /**
     * Set the crawl queue to use.
     *
     * @param CrawlQueueInterface $queue
     * @return void
     */
    public function setCrawlQueue(CrawlQueueInterface $queue)
    {
        $this->queue = $queue;
    }

    /**
     * Get the registered processors.
     *
     * @return ProcessorInterface[]
     */
    public function getProcessors()
    {
        return $this->processors;
    }

    /**
     * Add a processor instance to the crawler.
     *
     * @param ProcessorInterface $processor The processor to add
     * @return void
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * Add multiple processors to the crawler.
     * Duplicate processors will be overridden by the new one.
     *
     * @param ProcessorInterface[] $processors The processors to add
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addProcessors(array $processors)
    {
        Assert::allIsInstanceOf($processors, ProcessorInterface::class);
        $this->processors = array_merge($this->processors, $processors);
    }

    /**
     * Set the processor array directly.
     *
     * @param ProcessorInterface[] $processors The processors to set
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setProcessors(array $processors)
    {
        Assert::allIsInstanceOf($processors, ProcessorInterface::class);
        $this->processors = $processors;
    }

    /**
     * Get the registered validators.
     *
     * @return ValidatorInterface[]
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * Add a validator instance to the crawler.
     *
     * @param ProcessorInterface $validator The validator to add
     * @return void
     */
    public function addValidator(ValidatorInterface $validator)
    {
        $this->validators[] = $validator;
    }

    /**
     * Add multiple validators to the crawler.
     * Duplicate validators will be overridden by the new one.
     *
     * @param ValidatorInterface[] $validators The validators to add
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addValidators(array $validators)
    {
        Assert::allIsInstanceOf($validators, ValidatorInterface::class);
        $this->validators = array_merge($this->validators, $validators);
    }

    /**
     * Set the validator array directly.
     *
     * @param ValidatorInterface[] $validators The validators to set
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setValidators(array $validators)
    {
        Assert::allIsInstanceOf($validators, ValidatorInterface::class);
        $this->validators = $validators;
    }
}
