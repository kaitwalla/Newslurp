<?php

namespace Technical_peguins\Newslurp\Route;

// show all php errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Flight;
use Technical_penguins\Newslurp\Action\Authenticate;
use Technical_penguins\Newslurp\Action\Ingest;
use Technical_penguins\Newslurp\Controller\Page;
use Technical_penguins\Newslurp\Controller\Story;

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
    $data = Flight::request()->getBody();
    file_put_contents(__DIR__ . '/data', $data);
    if ($data) {
        Ingest::handle(json_decode($data, true));
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
    Page::load('public/rss.twig', ['url' => $_ENV['url'], 'stories' => $stories]);
});

Flight::start();
