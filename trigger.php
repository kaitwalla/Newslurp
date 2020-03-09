<?php
namespace Technical_penguins\Newslurp;

require_once(__DIR__ . '/_autoload.php');

use Flight;
use Technical_penguins\Newslurp\Action\Gmail;
Use Technical_penguins\Newslurp\Controller\User;

$gmail = new Gmail();
Flight::redirect('/');