<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(["namespace" => "Auth"], function() use ($router) {
    $router->post('/login', 'UserController@login');
    $router->get('/check', 'UserController@check');
    $router->post('/logout', 'UserController@logout');
});

$router->group(["middleware" => "auth"], function() use ($router) {
    $router->group(["namespace" => "Setting"], function() use ($router) {
        $router->post('/setting', 'IndexController@store');
        $router->get('/setting/{key}', 'IndexController@show');
    });
    $router->group(["namespace" => "News"], function() use ($router) {
        $router->get('/news', 'IndexController@index');
        $router->get('/news/chart', 'ChartController@index');
        $router->get('/news/{id}', 'IndexController@show');
    });
});