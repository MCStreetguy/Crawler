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
use MCStreetguy\Crawler\Processing\Validation\ValidatorInterface;
use GuzzleHttp\Client;
use MCStreetguy\Crawler\Result\CrawlResult;
use MCStreetguy\Crawler\Result\ResultSet;
use MCStreetguy\Crawler\Exceptions\ContentTooLargeException;
use MCStreetguy\Crawler\Stream\NullStream;
use GuzzleHttp\Exception\RequestException;
use MCStreetguy\Crawler\Exceptions\CrawlerException;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use MCStreetguy\Crawler\Processing\Validation\Core\RobotsTxtValidator;

/**
 * The main class of the web-crawler.
 *
 * @api
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class Crawler
{
    const USER_AGENT = 'MCStreetguy-Crawler/1.0';

    /** @var CrawlConfigurationInterface The crawler configuration object. */
    protected $configuration;

    /** @var CrawlQueueInterface The crawl queue */
    protected $queue;

    /** @var Seeker The link-seeker */
    protected $seeker;

    /** @var ProcessorInterface[] The registered processors */
    protected $processors;

    /** @var Client The http client */
    protected $client;

    /** @var UriInterface|null The target url to crawl. */
    protected $target;

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
        
        if ($queue === null) {
            $queue = new CrawlQueue;
        }
                
        if (!empty($processors)) {
            Assert::allIsInstanceOf($processors, ProcessorInterface::class);
        }

        if (!empty($validators)) {
            Assert::allIsInstanceOf($validators, ValidatorInterface::class);
        }
        
        $this->configuration = $config;
        $this->queue = $queue;
        $this->processors = $processors;
        
        $this->seeker = new Seeker($validators);
        $this->client = new Client([
            'allow_redirects' => [
                'max' => 5,
                'strict' => true,
                'referer' => false,
                'protocols' => ['http','https'],
                'track_redirects' => true,
            ],
            'delay' => $this->configuration->getRequestDelay(),
            'headers' => [
                'User-Agent' => self::USER_AGENT,
                'X-Crawler-Request' => (string) Uuid::uuid4(),
            ],
            'http_errors' => false,
            'on_headers' => [$this, 'validateResponseSize'],
            // 'stream' => true,
            'synchronous' => true,
            'timeout' => $this->configuration->getRequestTimeout(),
            'verify' => false,
        ]);

        # explicitly set null values
        $this->target = null;
    }

    /**
     * Execute the crawler for the given target.
     *
     * @param string|UriInterface $target The target to crawl
     * @return ResultSet
     * @throws \InvalidArgumentException If a passed argument is invalid
     * @throws \RuntimeException If an unexpected error happened
     * @throws CrawlerException Any error occurred during the crawl
     */
    public function execute($target): ResultSet
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

        if (!$this->configuration->isRobotsTxtIgnored()) {
            $this->seeker->addValidator(new RobotsTxtValidator($target));
        }

        $results = [];
        // $current = $target;
        $current = $this->queue->getNext();
        $crawlCount = 0;

        if ($current === null) {
            throw new \RuntimeException('Something went wrong while starting the crawling process!', 1553774441674);
        }

        do {
            try {
                $response = $this->client->get($current);
                $furtherLinks = $this->seeker->browse($current, $response);

                $this->queue->addAll($furtherLinks);
            } catch (ContentTooLargeException $e) {
                $response = $response->withBody(new NullStream);
                $furtherLinks = [];
            } catch (RequestException $e) {
                throw new CrawlerException(
                    'Something went wrong while crawling the target!',
                    1553788201087,
                    $e
                );
            }

            $results[] = $result = new CrawlResult($current, $response, $furtherLinks);
            
            foreach ($this->processors as $processor) {
                $processor->invoke($result);
            }
            
            $this->queue->finish($current);

            if ($this->validateMaximumCrawlCount(++$crawlCount)) {
                break;
            }

            $current = $this->queue->getNext();
        } while ($current !== null);

        $resultSet = new ResultSet($results);

        return $resultSet;
    }

    /**
     * Validate if the current crawl count exceeds the maximum crawl count.
     *
     * @internal
     * @param int $current The current crawl count
     * @return bool If the maximum crawl count has been exceeded
     */
    protected function validateMaximumCrawlCount(int $current)
    {
        $maximum = $this->configuration->getMaximumCrawlCount();
        return ($maximum > 0 && $current > $maximum);
    }

    /**
     * Validate the given response in terms of content size.
     *
     * @internal
     * @param ResponseInterface $response
     * @return void
     * @throws ContentTooLargeException
     */
    public function validateResponseSize(ResponseInterface $response)
    {
        $maximum = $this->configuration->getMaximumResponseSize();
        $actual = floatval($response->getHeader('Content-Length')['value']);

        if ($actual > $maximum) {
            throw ContentTooLargeException::forSize($maximum, $actual);
        }
    }

    /**
     * Load the coresponding robots.txt file from the target uri.
     *
     * @internal
     * @return void
     */
    protected function loadRobotsTxt()
    {
        if ($this->target === null) {
            return;
        }

        $robotsUri = $this->target->withPath('/robots.txt')->withQuery('')->withFragment('');
        $response = $this->client->get((string) $robotsUri, [
            'allow_redirects' => true,
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode >= 300) {
            $restrictions = '';
        } else {
            $restrictions = (string) $response->getBody();
        }

        $parser = new \RobotsTxtParser($restrictions);
        $parser->setHttpStatusCode($response->getStatusCode());
        $parser->setUserAgent(self::USER_AGENT);

        $this->robots = $parser;
    }

    # Getters and setters

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
        return $this->seeker->getValidators();
    }

    /**
     * Add a validator instance to the crawler.
     *
     * @param ProcessorInterface $validator The validator to add
     * @return void
     */
    public function addValidator(ValidatorInterface $validator)
    {
        return $this->seeker->addValidator($validator);
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
        return $this->seeker->addValidators($validators);
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
        return $this->seeker->setValidators($validators);
    }
}
