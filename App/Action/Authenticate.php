<?php

namespace Technical_penguins\Newslurp\Action;

use Flight;

class Authenticate
{
    public static function handle(string $password): void
    {
        if (md5($password) === $_ENV['PASSWORD']) {
            $_SESSION['authenticated'] = true;
        } else {
            $_SESSION['error'] = 'Invalid password';
        }
        Flight::redirect('/');
    }
}
