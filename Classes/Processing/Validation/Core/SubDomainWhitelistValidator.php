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

namespace MCStreetguy\Crawler\Processing\Validation\Core;

use MCStreetguy\Crawler\Processing\Validation\AbstractValidator;

/**
 * Allows for any uri, as long as it's on the same domain as the base uri.
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class SubDomainWhitelistValidator extends AbstractValidator
{
    /** @inheritDoc */
    public function isValid(\Psr\Http\Message\UriInterface $target)
    {
        $baseHost = $this->baseUri->getHost();

        if (substr($baseHost, 0, 4) === 'www.') {
            $baseHost = substr($baseHost, 4);
        }

        return (substr_compare($target->getHost(), $baseHost, -strlen($baseHost), strlen($baseHost)) === 0);
    }
}
