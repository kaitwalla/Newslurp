<?php

use Spatie\FlareClient\Flare;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

session_start();

require_once(__DIR__ . '/../vendor/autoload.php');

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
try {
    $dotenv->load();
    $dotenv->required(['URL', 'PASSWORD', 'PROD', 'DB_TYPE'])->notEmpty();
    if ($_ENV['PROD']) {
        $flare = Flare::make($_ENV['FLARE_TOKEN'])->registerFlareHandlers();
    }
} catch (Exception $e) {
    throw new Exception('Error loading .env file: ' . $e->getMessage());
}

Flight::map('error', function ($e) {
    $whoops = new Run;
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->register();
    $whoops->handleException($e);
});

require_once(__DIR__ . '/../routes/web.php');
