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

namespace MCStreetguy\Crawler;

use GuzzleHttp\Psr7\Uri;
use MCStreetguy\Crawler\Processing\Validation\ValidatorInterface;
use PHPHtmlParser\Dom;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Webmozart\Assert\Assert;
use MCStreetguy\Crawler\Parser\HtmlParser;
use MCStreetguy\Crawler\Parser\GenericParser;
use MCStreetguy\Crawler\Parser\ParserInterface;
use MCStreetguy\Crawler\Parser\SitemapParser;

/**
 * The seeker class is responsible for searching links within response bodies.
 *
 * @internal
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class Seeker
{
    const CONTENT_TYPE_MAP = [
        'text/html' => HtmlParser::class,
        'sitemap.xml' => SitemapParser::class,
        'default' => GenericParser::class,
    ];
    
    /** @var ValidatorInterface[] The registered validators */
    protected $validators;

    public function __construct(array $validators = [])
    {
        if (!empty($validators)) {
            Assert::allIsInstanceOf($validators, ValidatorInterface::class);
        }

        $this->validators = $validators;
    }

    /**
     * Browse the given input for further links.
     *
     * @param UriInterface $uri The uri belonging to the given response
     * @param ResponseInterface $response The input to search through
     * @return UriInterface[]
     */
    public function browse(UriInterface $uri, ResponseInterface $response)
    {
        $body = $response->getBody();

        if ($body->getSize() <= 0) {
            return [];
        }

        $parserClass = self::CONTENT_TYPE_MAP['default'];
        $contentType = strtolower(explode(';', $response->getHeader('Content-Type')[0])[0]);

        if ($contentType === 'text/xml' && strpos($response->getBody()->read(100), 'http://www.sitemaps.org/schemas/sitemap') !== false) {
            $parserClass = self::CONTENT_TYPE_MAP['sitemap.xml'];
        } elseif (array_key_exists($contentType, self::CONTENT_TYPE_MAP)) {
            $parserClass = self::CONTENT_TYPE_MAP[$contentType];
        }

        Assert::implementsInterface($parserClass, ParserInterface::class);

        $links = [];

        foreach (call_user_func_array([$parserClass, 'invoke'], [$uri, $response]) as $link) {
            $isValid = true;

            foreach ($this->validators as $validator) {
                if (!$validator->isValid($link)) {
                    $isValid = false;
                    break;
                }
            }

            if ($isValid) {
                $links[] = $link;
            }
        }

        return $links;
    }

    /**
     * Get the registered validators.
     *
     * @return ValidatorInterface[]
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * Add a validator instance to the crawler.
     *
     * @param ValidatorInterface $validator The validator to add
     * @return void
     */
    public function addValidator(ValidatorInterface $validator)
    {
        $this->validators[] = $validator;
    }

    /**
     * Add multiple validators to the crawler.
     * Duplicate validators will be overridden by the new one.
     *
     * @param ValidatorInterface[] $validators The validators to add
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addValidators(array $validators)
    {
        Assert::allIsInstanceOf($validators, ValidatorInterface::class);
        $this->validators = array_merge($this->validators, $validators);
    }

    /**
     * Set the validator array directly.
     *
     * @param ValidatorInterface[] $validators The validators to set
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setValidators(array $validators)
    {
        Assert::allIsInstanceOf($validators, ValidatorInterface::class);
        $this->validators = $validators;
    }
}
