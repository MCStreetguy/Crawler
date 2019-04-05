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

namespace MCStreetguy\Crawler\Parser;

use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use PHPHtmlParser\Dom;
use GuzzleHttp\Psr7\Uri;

/**
 *
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class HtmlParser implements ParserInterface
{
    public static function invoke(UriInterface $uri, ResponseInterface $response): \Generator
    {
        $document = (new Dom)->loadStr((string) $response->getBody());

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

            yield $link;
        }
    }
}
