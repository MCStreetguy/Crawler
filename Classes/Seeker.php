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

use Psr\Http\Message\ResponseInterface;
use PHPHtmlParser\Dom;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;
use MCStreetguy\Crawler\Processing\Validation\ValidatorInterface;
use Webmozart\Assert\Assert;

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
     * @param string|ResponseInterface $input The input to search through
     * @return UriInterface[]
     * @throws \InvalidArgumentException
     */
    public function browse(UriInterface $uri, $input, bool $ignoreValidation = false)
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

            $isValid = true;

            foreach ($this->validators as $validator) {
                $isValid = $isValid && $validator->isValid($link);
            }

            if ($isValid || $ignoreValidation) {
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
