<?php
require '../vendor/autoload.php';

define('DEBUG_TIME', microtime(true));

$router = new App\Router(dirname(__DIR__) . '/views');
$router
    ->get('/blog', '/post/index', 'blog')
    ->get('/blog/category', '/category/show', 'category')
    ->run();