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

namespace MCStreetguy\Crawler;

use Psr\Http\Message\ResponseInterface;

/**
 * The seeker class is responsible for searching links within response bodies.
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class Seeker
{
    /**
     * Browse the given input for further links.
     *
     * @param string|ResponseInterface $input The input to search through
     * @return string[]
     * @throws \InvalidArgumentException
     */
    public function browse($input)
    {
        if ($input instanceof ResponseInterface) {
            $body = $input->getBody();

            if ($body->getSize() <= 0) {
                return [];
            }

            $input = (string) $body;
        } elseif (!is_string($input)) {
            $type = gettype($input);

            throw new \InvalidArgumentException(
                "\$input has to be a string or an instance of ResponseInterface, $type given!",
                1553768650569
            );
        }
    }
}
