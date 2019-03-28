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

namespace MCStreetguy\Crawler\Processing;

use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A standard interface for processor classes.
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
interface ProcessorInterface
{
    /**
     * Invoke this processor.
     *
     * @param UriInterface $uri The uri that has been processed
     * @param ResponseInterface $response The response of the request to the processed uri
     * @return void
     */
    public function invoke(UriInterface $uri, ResponseInterface $response);
}
