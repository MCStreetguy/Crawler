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

namespace MCStreetguy\Crawler\Processing\Validation\Core;

use MCStreetguy\Crawler\Processing\Validation\ValidatorInterface;
use Psr\Http\Message\UriInterface;
use MCStreetguy\Crawler\Crawler;
use GuzzleHttp\Client;

/**
 * A validator class that ensures the given uri is not restricted.
 *
 * @internal
 * @author     Maximilian Schmidt <maximilianschmidt404@gmail.com>
 * @copyright  2019, Maximilian Schmidt
 * @license    https://github.com/MCStreetguy/Crawler/blob/master/LICENSE MIT
 * @package    mcstreetguy/crawler
 * @since      always
 */
class RobotsTxtValidator extends AbstractValidator implements ValidatorInterface
{
    /** @var \RobotsTxtParser */
    protected $robots;

    /** @inheritDoc */
    public function __construct(UriInterface $baseUri)
    {
        parent::__construct($baseUri);

        $this->robots = $this->loadRobotsTxt($baseUri);
    }

    /**
     * Load the robots.txt file from the target uri.
     *
     * @return \RobotsTxtParser
     */
    protected function loadRobotsTxt(UriInterface $target)
    {
        $robotsUri = $target->withPath('/robots.txt')->withQuery('')->withFragment('');
        $httpClient = new Client([
            'allow_redirects' => true,
            'http_errors' => false,
            'synchronous' => true,
            'verify' => false,
        ]);
        
        $response = $httpClient->get((string) $robotsUri);
        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode >= 300) {
            $restrictions = '';
        } else {
            $restrictions = (string) $response->getBody();
        }

        $parser = new \RobotsTxtParser($restrictions);
        $parser->setHttpStatusCode($response->getStatusCode());
        $parser->setUserAgent(Crawler::USER_AGENT);

        return $parser;
    }

    /** @inheritDoc */
    public function isValid(UriInterface $target)
    {
        return $this->robots->isAllowed($target);
    }
}
