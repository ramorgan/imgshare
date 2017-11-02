<?php

ini_set('display_errors', 0);
if (!file_exists(__DIR__ . '/../config/settings.json')) {
    die("config/settings.json does not exist");
}

require_once __DIR__.'/../vendor/autoload.php';

ini_set('display_errors', 'On');
$app = new imgshare\App();
$app['debug'] = true;
$app->run();
