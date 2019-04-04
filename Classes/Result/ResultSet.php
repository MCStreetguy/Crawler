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

use Tree\Node\Node;
use Tree\Node\NodeInterface;
use Webmozart\Assert\Assert;
use Psr\Http\Message\UriInterface;
use Tree\Builder\NodeBuilder;

/**
 * A set of CrawlResult objects.
 *
 * @api
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class ResultSet implements \Iterator
{
    /** @var UriInterface The base uri of the related crawl */
    protected $baseUri;

    /** @var CrawlResult[] The CrawlResult objects associated with this ResultSet */
    protected $contents;

    /** @var int The number of uris that have been crawled */
    protected $urisCrawled;

    /** @var int The number of uris that have been crawled successfully */
    protected $urisSucceeded;

    /** @var int The number of uris that failed crawling */
    protected $urisFailed;

    /** @var int The number of invalid uris that have been dropped */
    protected $droppedUris;

    /** @var int The current iterator index */
    protected $index;

    /**
     * Constructs a new instance
     *
     * @param CrawlResult[] The crawl results of this set
     * @return void
     */
    public function __construct(UriInterface $baseUri, $contents = [])
    {
        Assert::isArray($contents);
        Assert::allNumeric(array_keys($contents));
        Assert::allIsInstanceOf($contents, CrawlResult::class);

        $this->baseUri = $baseUri;
        $this->contents = $contents;
        $this->rewind();
    }

    /**
     * Get the base uri of this ResultSet.
     *
     * Get the base uri of this ResultSet.
     *
     * @return UriInterface
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * Get the contents of this ResultSet as an array.
     *
     * Get the contents of this ResultSet as an array.
     *
     * @return CrawlResult[]
     */
    public function toArray()
    {
        return $this->contents;
    }

    /**
     * Get the stats of this ResultSet as an associative array.
     *
     * Get the stats of this ResultSet as an associative array.
     *
     * @return array
     */
    public function getStats()
    {
        return [];
    }

    /**
     * Get the number of uris that have been crawled totally.
     *
     * Get the number of uris that have been crawled totally.
     *
     * @return int
     */
    public function getUrisCrawled()
    {
        return $this->urisCrawled;
    }

    /**
     * Get the number of uris that have been crawled successfully.
     *
     * Get the number of uris that have been crawled successfully.
     *
     * @return int
     */
    public function getUrisSucceeded()
    {
        return $this->urisSucceeded;
    }

    /**
     * Get the number of uris that failed crawling.
     *
     * Get the number of uris that failed crawling.
     *
     * @return int
     */
    public function getFailedUris()
    {
        return $this->urisFailed;
    }

    /**
     * Get the number of invalid uris that have been dropped.
     *
     * Get the number of invalid uris that have been dropped.
     *
     * @return int
     */
    public function getDroppedUris()
    {
        return $this->droppedUris;
    }

    /**
     * Find one result by it's corresponding uri.
     *
     * Find one result by it's corresponding uri.
     *
     * @return CrawlResult|null
     */
    public function findByUri(UriInterface $uri)
    {
        foreach ($this->contents as $result) {
            if ($result->getUri() === $uri) {
                return $result;
            }
        }

        return null;
    }

    /**
     * Convert this ResultSet to a Node tree structure.
     *
     * Convert this ResultSet to a Node tree structure.
     *
     * @return NodeInterface The root node of the tree
     */
    public function toNodeTree(): NodeInterface
    {
        $rootResult = null;

        foreach ($this->contents as $elem) {
            if ($elem->getUri() === $this->baseUri) {
                $rootResult = $elem;
                break;
            }
        }

        if ($rootResult === null) {
            throw new \RuntimeException('Cannot determine root node of crawl!', 1554402649187);
        }

        $rootNode = new Node((string) $rootResult->getUri());
        $tree = new NodeBuilder($rootNode);

        return $rootNode;
    }

    /**
     * Returns the current element.
     *
     * Returns the current element.
     *
     * @return CrawlResult
     * @link http://php.net/manual/en/iterator.current.php
     * @internal
     */
    public function current()
    {
        return $this->contents[$this->index];
    }

    /**
     * Returns the key of the current element.
     *
     * Returns the key of the current element.
     *
     * @return int
     * @link http://php.net/manual/en/iterator.key.php
     * @internal
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Moves the current position to the next element.
     *
     * Moves the current position to the next element.
     *
     * @return void
     * @link http://php.net/manual/en/iterator.next.php
     * @internal
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     * Rewinds back to the first element of the Iterator.
     *
     * Rewinds back to the first element of the Iterator.
     *
     * @return void
     * @link http://php.net/manual/en/iterator.rewind.php
     * @internal
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * Checks if current position is valid.
     *
     * Checks if current position is valid.
     *
     * @return bool
     * @link http://php.net/manual/en/iterator.valid.php
     * @internal
     */
    public function valid()
    {
        return isset($this->contents[$this->index]);
    }
}
