<?php
include '../vendor/autoload.php';

use GuzzleHttp\Psr7\Uri;
use MCStreetguy\Crawler\Crawler;
use MCStreetguy\Crawler\Processing\ProcessorInterface;
use MCStreetguy\Crawler\Processing\Validation\Core\DomainWhitelistValidator;
use MCStreetguy\Crawler\Processing\Validation\Core\SubDomainWhitelistValidator;
use MCStreetguy\Crawler\Processing\Validation\ValidatorInterface;

$uri1 = new Uri('http://www.obis-concept.de/');
$uri2 = new Uri('http://deploy.obis-concept.de/');
$uri3 = new Uri('http://lvr.concept-hosting.de/');
$uri4 = new Uri('http://rek.lvr.concept-hosting.de/');
$uri5 = new Uri('https://www.obis-concept.de/de/impressum.html');

\Kint::dump($uri1->getHost(), $uri2->getHost(), $uri3->getHost(), $uri4->getHost());

$test1 = new SubDomainWhitelistValidator($uri1);
$test2 = new SubDomainWhitelistValidator($uri3);

\Kint::dump($test1->isValid($uri2));
\Kint::dump($test1->isValid($uri3));
\Kint::dump($test1->isValid($uri4));
\Kint::dump($test2->isValid($uri1));
\Kint::dump($test2->isValid($uri2));
\Kint::dump($test2->isValid($uri4));

$test3 = new DomainWhitelistValidator($uri1);
\Kint::dump($test3->isValid($uri1));
\Kint::dump($test3->isValid($uri2));
\Kint::dump($test3->isValid($uri3));
\Kint::dump($test3->isValid($uri4));
\Kint::dump($test3->isValid($uri5));
