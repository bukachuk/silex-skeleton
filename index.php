<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
date_default_timezone_set('UTC');
$app = require_once __DIR__.'/src/app.php';

$app->run();