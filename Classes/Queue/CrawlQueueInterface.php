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
 * Specifies a standard for crawl queues.
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
interface CrawlQueueInterface
{
    /**
     * Adds an uri to the queue.
     *
     * Adds an uri to the queue.
     *
     * @param UriInterface $uri The uri to add
     * @return void
     */
    public function add(UriInterface $uri);

    /**
     * Check if the given uri is already in the queue.
     *
     * Check if the given uri is already in the queue.
     *
     * @param UriInterface $uri The uri to check for
     * @return bool If the uri has been found
     */
    public function has(UriInterface $uri): bool;

    /**
     * Check if the given uri is already in the queue, ignoring if it is finished already.
     *
     * Check if the given uri is already in the queue, ignoring if it is finished already.
     *
     * @param UriInterface $uri The uri to check for
     * @return bool If the uri has been found
     */
    public function hasAny(UriInterface $uri): bool;

    /**
     * Revoke the given uri from the queue if possible.
     *
     * Revoke the given uri from the queue if possible.
     *
     * @param UriInterface $uri The uri to revoke
     * @return void
     */
    public function revoke(UriInterface $uri);

    /**
     * Check if there is another uri in the queue.
     *
     * Check if there is another uri in the queue.
     *
     * @return bool If there is a next element
     */
    public function hasNext(): bool;

    /**
     * Get the next uri in this queue.
     *
     * Get the next uri in this queue.
     *
     * @return null|UriInterface The next uri element (if available)
     */
    public function getNext();

    /**
     * Get all uris in this queue.
     *
     * Get all uris in this queue.
     *
     * @return UriInterface[] The uris in this queue
     */
    public function getAll(): array;

    /**
     * Get all pending uris in this queue.
     *
     * Get all pending uris in this queue.
     *
     * @return UriInterface[] The uris in this queue
     */
    public function getAllPending(): array;

    /**
     * Get all finished uris in this queue.
     *
     * Get all finished uris in this queue.
     *
     * @return UriInterface[] The uris in this queue
     */
    public function getAllFinished(): array;

    /**
     * Clear all uris from the queue.
     *
     * Clear all uris from the queue.
     *
     * @return void
     */
    public function clear();

    /**
     * Clear all pending uris from the queue.
     *
     * Clear all pending uris from the queue.
     *
     * @return void
     */
    public function clearPending();

    /**
     * Clear all finished uris from the queue.
     *
     * Clear all finished uris from the queue.
     *
     * @return void
     */
    public function clearFinished();

    /**
     * Mark an uri as finished.
     *
     * Mark an uri as finished.
     *
     * @param UriInterface $uri The uri to mark as finished
     * @return void
     */
    public function finish(UriInterface $uri);
}
