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

namespace MCStreetguy\Crawler\Exceptions;

/**
 * The content of the response is too large to be downloaded.
 * This exception signals the crawler to continue with the process but drop the response body.
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 * @internal
 */
class ContentTooLargeException extends CrawlerException
{
    /**
     * Creates an instance of ContentTooLargeException with a predefined error message.
     *
     * Creates an instance of ContentTooLargeException with a predefined error message according to the passed arguments.
     *
     * @param float $maximum The maximum response size allowed
     * @param float $actual The actual response size given
     * @param string|null $uri The uri which has been downloaded
     * @return self
     */
    public static function forSize(float $maximum, float $actual, string $uri = null)
    {
        $mixin = ($uri !== null ? "for '$uri' " : '');
        
        return new self(
            "HTTP response ${mixin}exceeds the maximum response size! " .
            "Allowed: $maximum, Recieved: $actual",
            1553785039042
        );
    }
}
