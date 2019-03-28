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

namespace MCStreetguy\Crawler\Queue;

use Psr\Http\Message\UriInterface;

/**
 * The default crawl queue.
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class CrawlQueue implements CrawlQueueInterface
{
    /** @var UriInterface[] The unprocessed uris in this queue */
    protected $pending;

    /** @var UriInterface[] The processed uris in this queue */
    protected $finished;

    /**
     * Constructs a new instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->clear();
    }

    /** @inheritDoc */
    public function add(UriInterface $uri)
    {
        if ($this->hasAny($uri)) {
            return;
        }

        $this->pending[] = $uri;
    }

    /** @inheritDoc */
    public function has(UriInterface $uri): bool
    {
        return in_array($uri, $this->pending);
    }

    /** @inheritDoc */
    public function hasAny(UriInterface $uri): bool
    {
        return (in_array($uri, $this->pending) || in_array($uri, $this->finished));
    }

    /** @inheritDoc */
    public function revoke(UriInterface $uri)
    {
        $index = array_search($uri, $this->pending);

        if ($index === false) {
            return;
        }

        unset($this->pending[$index]);
    }

    /** @inheritDoc */
    public function hasNext(): bool
    {
        return (count($this->pending) > 0);
    }

    /** @inheritDoc */
    public function getNext()
    {
        return $this->hasNext() ? array_pop(array_reverse($this->pending)) : null;
    }

    /** @inheritDoc */
    public function getAll(): array
    {
        return array_merge($this->finished, $this->pending);
    }

    /** @inheritDoc */
    public function getAllPending(): array
    {
        return $this->pending;
    }

    /** @inheritDoc */
    public function getAllFinished(): array
    {
        return $this->finished;
    }

    /** @inheritDoc */
    public function clear()
    {
        $this->clearPending();
        $this->clearFinished();
    }

    /** @inheritDoc */
    public function clearPending()
    {
        $this->pending = [];
    }

    /** @inheritDoc */
    public function clearFinished()
    {
        $this->finished = [];
    }

    /** @inheritDoc */
    public function finish(UriInterface $uri)
    {
        if (!$this->has($uri)) {
            return;
        }

        $this->finished[] = $uri;
        $this->revoke($uri);
    }
}
