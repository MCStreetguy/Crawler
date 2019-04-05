<?php
include '../vendor/autoload.php';

use MCStreetguy\Crawler\Crawler;
use MCStreetguy\Crawler\Processing\ProcessorInterface;
use MCStreetguy\Crawler\Processing\Validation\Core\DomainWhitelistValidator;
use GuzzleHttp\Psr7\Uri;

class DebugProcessor implements ProcessorInterface
{
    public function invoke(\MCStreetguy\Crawler\Result\CrawlResult $result)
    {
        echo 'Crawled: ' . (string) $result->getUri() . PHP_EOL;
    }
}

// const TARGET_URI = 'http://example.com/';
const TARGET_URI = 'https://demo.mcstreetguy.de/';
// const TARGET_URI = 'https://www.kampf.de/';
// const TARGET_URI = 'https://demo.mcstreetguy.de/sitemap.xml';

$target = new Uri(TARGET_URI);

$crawler = new Crawler();
$crawler->addProcessor(new DebugProcessor);
$crawler->addValidator(new DomainWhitelistValidator($target));
$resultSet = $crawler->execute($target);

if (in_array('--debug', $argv)) {
    \Kint::dump($resultSet);
}

echo 'Done.' . PHP_EOL;
exit;
