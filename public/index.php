<?php
namespace Technical_penguins\Newslurp;

session_start();

require_once(__DIR__ . '/../_autoload.php');

use Flight;

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

if (file_exists(__DIR__ . '/../env.php')) {
    require_once(__DIR__ . '/../env.php');
} else {
    throw new \Exception('ENV file not created');
}

if (!file_exists(__DIR__ . '/../client_secret.json')) {
    throw new \Exception('Client Secret file not found');
}

Flight::map('error', function($e){
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
    $whoops->handleException($e);
});

require_once(__DIR__ . '/../routes/web.php');

