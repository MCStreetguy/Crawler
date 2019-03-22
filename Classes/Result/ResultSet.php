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

use Webmozart\Assert\Assert;
use Tree\Builder\NodeBuilder;
use Tree\Node\NodeInterface;
use Tree\Node\Node;

/**
 * A set of CrawlResult objects.
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class ResultSet implements \Iterator
{
    /** @var CrawlResult[] The CrawlResult objects associated with this ResultSet */
    protected $contents;

    /** @var int The current iterator index */
    protected $index;

    /**
     * Constructs a new instance
     *
     * @param CrawlResult[] The crawl results of this set
     * @return void
     */
    public function __construct($contents = [])
    {
        Assert::isArray($contents);
        Assert::allNumeric(array_keys($contents));
        Assert::allIsInstanceOf($contents, CrawlResult::class);

        $this->contents = $contents;
        $this->rewind();
    }

    /**
     * Returns the current element.
     *
     * @return CrawlResult
     * @link http://php.net/manual/en/iterator.current.php
     */
    public function current()
    {
        return $this->contents[$this->index];
    }

    /**
     * Returns the key of the current element.
     *
     * @return int
     * @link http://php.net/manual/en/iterator.key.php
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Moves the current position to the next element.
     *
     * @return void
     * @link http://php.net/manual/en/iterator.next.php
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     * Rewinds back to the first element of the Iterator.
     *
     * @return void
     * @link http://php.net/manual/en/iterator.rewind.php
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * Checks if current position is valid.
     *
     * @return bool
     * @link http://php.net/manual/en/iterator.valid.php
     */
    public function valid()
    {
        return isset($this->contents[$this->index]);
    }

    /**
     * Convert this ResultSet to a Node tree structure.
     *
     * @return NodeInterface The root node of the tree
     */
    public function toNodeTree(): NodeInterface
    {
        $contents = clone $this->contents;
        $rootResult = array_filter($contents, function ($elem) {
            return ($elem->getFoundOn() === null);
        })[0];

        $rootNode = new Node($rootResult->getUri());
        // $tree = new NodeBuilder($rootNode);

        return $rootNode;
    }
}
