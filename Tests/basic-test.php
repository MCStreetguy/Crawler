<?php

use MCStreetguy\Crawler\Crawler;
use MCStreetguy\Crawler\Processing\ProcessorInterface;

include_once '../vendor/autoload.php';

class DebugProcessor implements ProcessorInterface
{
    public function invoke(\MCStreetguy\Crawler\Result\CrawlResult $result)
    {
        echo 'Crawled: ' . (string)$result->getUri() . PHP_EOL;
    }
}


$crawler = new Crawler();
$crawler->addProcessor(new DebugProcessor);
$crawler->execute('https://demo.mcstreetguy.de/');

exit;
