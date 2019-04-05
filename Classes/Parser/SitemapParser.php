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
use PHPHtmlParser\Dom\AbstractNode;

/**
 *
 *
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class SitemapParser implements ParserInterface
{
    public static function invoke(UriInterface $uri, ResponseInterface $response): \Generator
    {
        $document = (new Dom)->loadStr((string) $response->getBody());

        /** @var AbstractNode $textNode */
        foreach ($document->find('loc') as $node) {
            $link = $node->text;

            yield new Uri($link);
        }
    }
}
