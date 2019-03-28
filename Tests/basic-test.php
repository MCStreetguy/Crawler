<?php
include '../vendor/autoload.php';

use MCStreetguy\Crawler\Crawler;
use MCStreetguy\Crawler\Processing\ProcessorInterface;
use MCStreetguy\Crawler\Processing\Validation\ValidatorInterface;

class DebugProcessor implements ProcessorInterface
{
    public function invoke(\MCStreetguy\Crawler\Result\CrawlResult $result)
    {
        echo 'Crawled: ' . (string)$result->getUri() . PHP_EOL;
    }
}

class DebugValidator implements ValidatorInterface
{
    protected $baseUri;

    public function __construct(string $baseUri)
    {
        $this->baseUri = $baseUri;
    }

    public function isValid(\Psr\Http\Message\UriInterface $target)
    {
        return (substr_compare((string) $target, $this->baseUri, 0) === 0);
    }
}

const TARGET_URI = 'http://example.com/';

$crawler = new Crawler();
$crawler->addProcessor(new DebugProcessor);
$crawler->addValidator(new DebugValidator(TARGET_URI));
$crawler->execute(TARGET_URI);

exit;
