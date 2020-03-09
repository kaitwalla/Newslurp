<?php

namespace Technical_penguins\Newslurp\Action;

use Flight;
use Google_Client;
use Google_Service_Gmail;
use PhpMimeMailParser\Parser;
Use Technical_penguins\Newslurp\Controller\Database;
Use Technical_penguins\Newslurp\Controller\Story as StoryController;
use Technical_penguins\Newslurp\Model\Email;
use Technical_penguins\Newslurp\Model\Story;
use Technical_penguins\Newslurp\Controller\User;

class Gmail {
    var $access_token;
    var $email;
    var $label_id;
    var $messages = [];
    var $new_messages;
    var $refresh_token;
    var $service;
    var $user_id;

    public function __construct() {
        if (User::is_installation_activated()) {
            if (!empty($_SESSION)) {
                $gmail_values = User::load_values_from_session();
            } else {
                $gmail_values = User::get_credentials();
            }
            foreach ($gmail_values as $param=>$value) {
                $this->{$param} = $value;
            }
            $client = new Google_Client();
            $client->setAuthConfig(__DIR__ . '/../client_secret.json');
            $client->addScope(Google_Service_Gmail::MAIL_GOOGLE_COM);
            $client->addScope('email');
            $client->addScope('profile');
            $client->setRedirectUri($_ENV['url'] . '/callback');
            $client->setAccessType('offline');   
            $client->setIncludeGrantedScopes(true);
            $token = $client->refreshToken($gmail_values['refresh']);
            if (isset($token['access_token']) && $token['access_token'] != $gmail_values['access']) {
                User::update_token('access',$token['access_token'], $this->email);
                if (!empty($_SESSION)) {
                    $_SESSION['access'] = $token['access_token'];
                }
                $this->access = $token['access_token'];
            }
            if (isset($token['refresh_token']) && $token['refresh_token'] != $this->refresh) {
                User::update_token('refresh',$token['refresh_token'], $this->email);
                if (!empty($_SESSION)) {
                    $_SESSION['refresh'] = $token['refresh_token'];
                }
                $this->refresh = $token['refresh_token'];
            }
            $client->setAccessToken($this->access);
            $this->service = new Google_Service_Gmail($client);
            if (!isset($gmail_values['label_id'])) {
                $this->get_label_id();
            }
            $this->check_for_new_messages();
            if ($this->new_messages > 0) {
                $this->process_messages();
            }
        } else {
            Flight::redirect('/');
        }
        
    }

    private function check_for_new_messages() {
        $label = $this->service->users_labels->get($this->user_id, $this->label_id);
        $this->new_messages = $label->getMessagesTotal();
        $pageToken = NULL;
        if ($this->new_messages > 0) {
            do {
                $params = ['labelIds'=> $this->label_id,'maxResults'=>1000];
                if ($pageToken) {
                    $params['pageToken'] = $pageToken;
                }            
                $messageResponse = $this->service->users_messages->listUsersMessages($this->user_id, $params);
                if ($messageResponse->getMessages()) {
                    $this->messages = array_merge($this->messages, $messageResponse->getMessages());
                    $pageToken = $messageResponse->getNextPageToken();
                }
            } while ($pageToken !== NULL);            
        }

    }

    private function get_label_id() {
        $labels = $this->service->users_labels->listUsersLabels($this->user_id);
        foreach ($labels->labels as $label) {
            if (strtolower($label->name) == 'newsletters') {
                $label_id = $label->id;
                $this->label_id = $label_id;
                Database::query('UPDATE (label_id) values (?) WHERE email=?', [$this->email, $this->label_id]);
                return;
            }  
        }
    }

    private function process_messages() {
        foreach ($this->messages as $message) {
            
            $message_content = $this->service->users_messages->get($this->user_id, $message->id, ['format'=>'raw']);
            
            $raw = base64_decode(str_replace(['-', '_'], ['+', '/'], $message_content->raw));
            $parser = (new Parser)->setText($raw);
            $fields = ['subject', 'from', 'date'];
            $story_details = new \stdClass();
    
            $story_details->title = $parser->getHeader('subject');
            $story_details->author = $parser->getHeader('from');
            $story_details->date = $parser->getHeader('date');
            $story_details->description = $message_content->snippet;
            
            $story_details->content = $parser->getMessageBody('html');
            $story = new Story($story_details);
            StoryController::save($story);
            $this->service->users_messages->trash($this->user_id, $message->id);
        }
    }
}