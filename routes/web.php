<?php
namespace Technical_peguins\Newslurp\Route;

use Flight;
use Technical_penguins\Newslurp\Action\Authorize;
use Technical_penguins\Newslurp\Action\Callback;
use Technical_penguins\Newslurp\Action\Gmail;
use Technical_penguins\Newslurp\Controller\Page;
use Technical_penguins\Newslurp\Controller\Story;
use Technical_penguins\Newslurp\Controller\User;

Flight::route('/', function(){
    $activated = User::is_installation_activated();
    $params = [];
    if ($activated) {
        $params['activated'] = true;
        if ($user = User::is_authorized()) {
            $params['user'] = $user;
            $params['stories'] = Story::get_stories(0);
        }   
        if ($user) {
            $params['email'] = $_SESSION['email'];
        }
    } else {
        $params['activated'] = false;
    }
    if (isset($_SESSION['error'])) {
        $params['error'] = $_SESSION['error'];
        session_unset('error');
    }
    Page::load('public/index.twig', $params);
});

Flight::route('/authorize', function(){
    Authorize::authorize();
});

Flight::route('/logout', function(){
    Authorize::logout();
    Flight::redirect('/');
});

Flight::route('/callback', function(){
    Callback::authenticate();
});

Flight::route('/update', function(){
    $gmail = new Gmail();    
    Flight::redirect('/list');
});

Flight::route('/list', function() {
    $stories = Story::get_stories(0);
    $max_pages = ceil( Story::get_count()/10 );
    Page::load('public/story_list.twig', ['stories' => $stories, 'max_pages'=>$max_pages, 'page'=>1]);
});

Flight::route('/list/@page', function($page=0) {
   $limit = $page - 1;
   $stories = Story::get_stories($limit);
   $max_pages = ceil( Story::get_count()/10 );
   Page::load('public/story_list.twig', ['stories' => $stories, 'max_pages'=>$max_pages, 'page'=>$page]);
});

Flight::route('/story/@id', function($id){
    $story = Story::load($id);
    Page::load('public/story.twig', ['story' => $story]);
});

Flight::route('/rss', function() {
    $stories = Story::get_rss_stories();
    header('Content-Type: application/xml; charset=utf-8');
    Page::load('public/rss.twig', ['url'=>$_ENV['url'], 'stories'=>$stories]);
});

Flight::start();