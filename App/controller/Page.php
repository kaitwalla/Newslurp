<?php

namespace Technical_penguins\Newslurp\Controller;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class Page
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public static function load(string $template, array $data = []): void
    {
        $twigLoader = new FilesystemLoader(__DIR__ . '/../../templates');
        $twigObject = new Environment($twigLoader, [
            //'cache' => __DIR__ . '/../cache',
        ]);
        $timestampFilter = new TwigFilter('timestamp', function ($timestamp) {
            return date('r', $timestamp);
        });

        $dateFilter = new TwigFilter('date', function ($timestamp) {
            return date('F j, g:i a', $timestamp);
        });

        $twigObject->addFilter($timestampFilter);
        $twigObject->addFilter($dateFilter);

        echo $twigObject->render($template, $data);
    }
}
