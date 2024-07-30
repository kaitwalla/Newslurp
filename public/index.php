<?php

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

session_start();

// show all php errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../vendor/autoload.php');

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

if (file_exists(__DIR__ . '/../env.php')) {
    require_once(__DIR__ . '/../env.php');
    if (!isset($_ENV['password'])) {
        throw new Exception('Password not set in ENV file');
    }
} else {
    throw new Exception('ENV file not created');
}

Flight::map('error', function ($e) {
    $whoops = new Run;
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->register();
    $whoops->handleException($e);
});

require_once(__DIR__ . '/../routes/web.php');
