<?php

namespace Technical_penguins\Newslurp\Action;

use Flight;
use Google_Client;
use Google_Service_Gmail;

class Authorize {
    public static function authorize() {
        if (!empty($_POST) && isset($_POST['email_address'])) {
            $client = new Google_Client();
            $client->setAuthConfig(__DIR__ . '/../client_secret.json');
            $client->addScope(Google_Service_Gmail::MAIL_GOOGLE_COM);
            $client->addScope('email');
            $client->addScope('profile');
            $client->setRedirectUri($_ENV['url'] . '/callback');
            $client->setAccessType('offline');   
            $client->setIncludeGrantedScopes(true);
            $client->setLoginHint($_POST['email_address']);
            $client->setApprovalPrompt("force");
            $auth_url = $client->createAuthUrl();
            Flight::redirect($auth_url);
        }
        else {
            Flight::redirect('/');
        }
    }
    public static function logout() {
        session_destroy();
    }
}