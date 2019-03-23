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

use Psr\Http\Message\UriInterface;
use Ramsey\Uuid\Uuid;
use Psr\Http\Message\ResponseInterface;

/**
 * Represents a crawl result.
 *
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

    /** @var null|UriInterface The uri on which this result has been found */
    protected $foundOn;

    /** @var ResponseInterface The response of the request to the uri of this result */
    protected $response;

    /**
     * Constructs a new instance.
     *
     * @param UriInterface $uri The uri of this CrawlResult
     * @param null|UriInterface $foundOn The uri on which this result has been found
     * @return void
     */
    public function __construct(UriInterface $uri, UriInterface $foundOn = null)
    {
        $this->uri = $uri;
        $this->foundOn = $foundOn;

        $identifier = (string) $uri;

        if ($foundOn !== null) {
            $identifier .= '_' . (string) $foundOn;
        }

        $this->identifier = Uuid::uuid5(self::NS, $identifier);
    }

    /**
     * Get the UUID representing this result
     *
     * @return string
     */
    public function getIdentifier() : string
    {
        return $this->identifier;
    }

    /**
     * Get the uri of this result.
     *
     * @return UriInterface
     */
    public function getUri() : UriInterface
    {
        return $this->uri;
    }

    /**
     * Get the parent uri of this result if available.
     *
     * @return null|UriInterface
     */
    public function getFoundOn()
    {
        return $this->foundOn;
    }

    /**
     * Get the response of the request to the uri of this result
     *
     * @return ResponseInterface
     */
    public function getResponse() : ResponseInterface
    {
        return $this->response;
    }
}
