<?php

namespace Technical_penguins\Newslurp\Action;

use Flight;
use Google_Client;
use Google_Service_Gmail;
use Technical_penguins\Newslurp\Controller\User;

class Callback {
    public static function authenticate() {
        $client = new Google_Client();
        $client->setAuthConfig(__DIR__ . '/../client_secret.json');
        $client->addScope(Google_Service_Gmail::MAIL_GOOGLE_COM);
        $client->addScope('email');
        $client->addScope('profile');
        $client->setRedirectUri($_ENV['url'] . '/callback');
        $client->setAccessType('offline');   
        $client->setIncludeGrantedScopes(true);
        $auth_url = $client->createAuthUrl();
        if (!empty($_GET) && isset($_GET['code'])) {
            $token = $client->authenticate($_GET['code']);
            $user_data = $client->verifyIdToken();
            $access = $token['access_token'];
            $id = $token['id_token'];
            $email = $user_data['email'];
            $user_id = $user_data['sub'];
            User::update($email, $access, $id, $user_id);
            if (isset($token['refresh_token'])) {
                User::update_token('refresh', $token['refresh_token'], $email);
                $_ENV['refresh'] = $token['refresh_token'];
            }
            Flight::redirect('/');
        }
        else {
            header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        }
    }
}