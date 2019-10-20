<?php

use App\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once 'vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
    'cache' => 'twig_cache/',
    'debug' => true
]);

$router = new Router($twig);
$router->process($_SERVER['PATH_INFO'], $_SERVER['QUERY_STRING']);