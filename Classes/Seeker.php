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
use PHPHtmlParser\Dom;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

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
     * @param UriInterface $uri The uri belonging to the given response
     * @param string|ResponseInterface $input The input to search through
     * @return UriInterface[]
     * @throws \InvalidArgumentException
     */
    public function browse(UriInterface $uri, $input)
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

        $document = (new Dom)->loadStr($input);
        $links = [];

        foreach ($document->find('[href]') as $node) {
            $tag = $node->getTag();

            if (!$tag->hasAttribute('href')) {
                continue;
            }

            $href = $tag->getAttribute('href')['value'];
            $link = new Uri($href);

            if (!Uri::isAbsolute($link)) {
                $link = Uri::resolve($uri, $link);
            }

            $links[] = $link;
        }

        return $links;
    }

    /**
     * Browse the given input for further links and reduce the result set to unique uris.
     *
     * @param UriInterface $uri The uri belonging to the given response
     * @param string|ResponseInterface $input The input to search through
     * @return UriInterface[]
     * @throws \InvalidArgumentException
     */
    public function distinct(UriInterface $uri, $input)
    {
        return array_unique($this->browse($uri, $input));
    }
}
