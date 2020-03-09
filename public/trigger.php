<?php
namespace Technical_penguins\Newslurp;

session_start();

require_once(__DIR__ . '/../_autoload.php');

use Flight;
use Technical_penguins\Newslurp\Action\Gmail;

$gmail = new Gmail();
Flight::redirect('/');