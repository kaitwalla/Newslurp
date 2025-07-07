<?php

namespace Technical_peguins\Newslurp\Route;

use Flight;
use Technical_penguins\Newslurp\Action\Authenticate;
use Technical_penguins\Newslurp\Action\Ingest;
use Technical_penguins\Newslurp\Controller\Page;
use Technical_penguins\Newslurp\Controller\Story;

$config = FlareConfig::make($_ENV['FLARE_TOKEN'])->trace();

Flare::make($config)->registerFlareHandlers();

Flight::route('/', function () {
    $params = [];
    if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
        $params['authenticated'] = true;
        $params['stories'] = Story::get_stories(0);
    }
    if (isset($_SESSION['error'])) {
        $params['error'] = $_SESSION['error'];
        unset($_SESSION['error']);
    }
    Page::load('public/index.twig', $params);
});

Flight::post('/ingest', function () {
    // Get the raw request body
    $data = Flight::request()->getBody();
    file_put_contents(__DIR__ . '/data', $data);
    if ($data && !empty($data)) {
        // Decode the JSON data from the request body
        // This works with the Google Apps Script: UrlFetchApp.fetch('https://newsletter.kait.dev/ingest', 
        // {muteHttpExceptions: false, method: 'post', payload: JSON.stringify(messageContents)})
        $decoded_data = json_decode($data, true);
        if (is_array($decoded_data)) {
            Ingest::handle($decoded_data);
        } else {
            // Log error or handle invalid JSON data
            error_log('Invalid JSON data received in /ingest endpoint');
        }
    }
});

Flight::post('/logout', function () {
    session_destroy();
    Flight::redirect('/');
});

Flight::post('/authenticate', function () {
    Authenticate::handle(Flight::request()->data['password']);
});

Flight::route('/list', function () {
    $stories = Story::get_stories(0);
    $max_pages = ceil(Story::get_count() / 10);
    Page::load('public/story_list.twig', ['stories' => $stories, 'max_pages' => $max_pages, 'page' => 1]);
});

Flight::route('/list/@page', function ($page = 0) {
    $limit = $page - 1;
    $stories = Story::get_stories($limit);
    $max_pages = ceil(Story::get_count() / 10);
    Page::load('public/story_list.twig', ['stories' => $stories, 'max_pages' => $max_pages, 'page' => $page]);
});

Flight::route('/story/@id', function ($id) {
    $story = Story::load($id);
    Page::load('public/story.twig', ['story' => $story]);
});

Flight::route('/rss', function () {
    $stories = Story::get_rss_stories();
    header('Content-Type: application/xml; charset=utf-8');
    Page::load('public/rss.twig', ['url' => $_ENV['URL'], 'stories' => $stories]);
});

Flight::start();
