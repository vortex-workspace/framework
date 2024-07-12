<?php

require_once __DIR__ . '/../vendor/autoload.php';

$applicationBuilder = new \Stellar\Boot\ApplicationBuilder(__DIR__ . '/..', __DIR__ . '/..');
$application = $applicationBuilder->createApp();
$application->build()->run();