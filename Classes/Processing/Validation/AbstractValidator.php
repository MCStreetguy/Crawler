<?php

/**
 * This file is part of the mcstreetguy/crawler package.
 *
 * @internal
 * @package    mcstreetguy/crawler
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @version    SVN: $Id$
 */

namespace MCStreetguy\Crawler\Processing\Validation;

use MCStreetguy\Crawler\Processing\Validation\ValidatorInterface;
use Psr\Http\Message\UriInterface;

/**
 * An abstract validator class for internal use.
 *
 * @internal
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
abstract class AbstractValidator implements ValidatorInterface
{
    /** @var UriInterface The base uri of the crawl */
    protected $baseUri;
    
    /**
     * Constructs a new instance
     *
     * @param UriInterface $baseUri The base uri of the crawl
     * @return void
     */
    public function __construct(UriInterface $baseUri)
    {
        $this->baseUri = $baseUri;
    }
}
