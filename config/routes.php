<?php declare(strict_types=1);

use App\Core\Http\Router;

$router = Router::getInstance();
$router->get('home', ['/', 'HomeController@index']);