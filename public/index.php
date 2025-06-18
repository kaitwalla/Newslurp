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

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
try {
    $dotenv->load();
    $dotenv->required(['URL', 'PASSWORD', 'DB_TYPE'])->notEmpty();
} catch (Exception $e) {
    throw new Exception('Error loading .env file: ' . $e->getMessage());
}

// Make environment variables available in $_ENV for backward compatibility
$_ENV['url'] = $_ENV['URL'];
$_ENV['password'] = $_ENV['PASSWORD'];

Flight::map('error', function ($e) {
    $whoops = new Run;
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->register();
    $whoops->handleException($e);
});

require_once(__DIR__ . '/../routes/web.php');
