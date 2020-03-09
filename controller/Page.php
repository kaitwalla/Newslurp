<?php

namespace Technical_penguins\Newslurp\Controller;

class Page {
    public static function load(string $template, array $data=[]) {
        global $twigObject;

        echo $twigObject->render($template, $data);
    }
}

$twigLoader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twigObject = new \Twig\Environment($twigLoader, [
    //'cache' => __DIR__ . '/../cache',
]);
$timestampFilter = new \Twig\TwigFilter('timestamp', function ($timestamp) {
    return date('r', $timestamp);
});

$twigObject->addFilter($timestampFilter);