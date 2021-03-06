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

namespace MCStreetguy\Crawler\Result;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Ramsey\Uuid\Uuid;
use GuzzleHttp\TransferStats;

/**
 * Represents a crawl result.
 *
 * @api
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class CrawlResult
{
    const NS = 'b51d4267-3ad5-530b-9abb-3e6ab107893c';

    /** @var string The UUID representing this result */
    protected $identifier;

    /** @var UriInterface The uri of this result */
    protected $uri;

    /** @var ResponseInterface The response of the request to the uri of this result */
    protected $response;

    /** @var UriInterface[] The links found within this results response */
    protected $links;

    /** @var TransferStats|null The transfer statistics of the sent request or null if not available */
    protected $stats;

    /**
     * Constructs a new instance.
     *
     * Constructs a new instance.
     *
     * @param UriInterface $uri The uri of this CrawlResult
     * @param ResponseInterface $response The response of the request to the given uri
     * @param null|UriInterface[] $links All links found within the response
     * @return void
     */
    public function __construct(
        UriInterface $uri,
        ResponseInterface $response,
        array $links = [],
        TransferStats $stats = null
    ) {
        $this->uri = $uri;
        $this->response = $response;
        $this->links = $links;
        $this->stats = $stats;

        $this->identifier = (string) Uuid::uuid5(self::NS, (string) $uri);
    }

    /**
     * Get the UUID representing this result
     *
     * Get the UUID representing this result
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Get the uri of this result.
     *
     * Get the uri of this result.
     *
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Get the response of the request to the uri of this result
     *
     * Get the response of the request to the uri of this result
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Get the links found within the response.
     *
     * Get the links found within the response.
     *
     * @return UriInterface[]
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Get the transfer statistics of the sent request if available.
     *
     * Get the transfer statistics of the sent request if available.
     *
     * @return TransferStats|null
     */
    public function getStatistics()
    {
        return $this->stats;
    }

    /**
     * Check if the bound request was successful.
     *
     * Check if the bound request was successful.
     *
     * @return bool
     */
    public function success()
    {
        $status = $this->response->getStatusCode();
        return ($status >= 200) && ($status < 300);
    }

    /**
     * Check if the bound request was a failure.
     *
     * Check if the bound request was a failure.
     *
     * @return bool
     */
    public function failed()
    {
        return !$this->success();
    }
}
