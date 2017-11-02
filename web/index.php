<?php

ini_set('display_errors', 0);
if (!file_exists(__DIR__ . '/../config/settings.json')) {
    die("config/settings.json does not exist");
}
require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../src/app.php';
require __DIR__.'/../config/prod.php';
require __DIR__.'/../src/controllers.php';
$app->run();
