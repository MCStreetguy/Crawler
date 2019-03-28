<?php

use MCStreetguy\Crawler\Crawler;

include_once '../vendor/autoload.php';

$crawler = new Crawler();
$crawler->execute('https://demo.mcstreetguy.de/');

exit;
