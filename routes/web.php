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
    $router->get('/check-user', 'UserController@check');
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
        $router->get('/news/chart/sentiment-summary', 'SentimentSummaryController@index');
        $router->get('/news/{id}', 'IndexController@show');
    });
    
    $router->group(["namespace" => "NewsSource"], function() use ($router) {
        $router->get('/news-source', 'IndexController@index');
    });

    $router->group(["namespace" => "Analysis"], function() use ($router) {
        $router->get('/analysis', 'IndexController@index');
        $router->post('/analysis', 'IndexController@store');
        $router->get('/analysis/{id}', 'IndexController@show');
        $router->put('/analysis/{id}', 'IndexController@update');
        $router->delete('/analysis/{id}', 'IndexController@destroy');
    });

    $router->group(["namespace" => "Event"], function() use ($router) {
        $router->get('/event', 'IndexController@index');
        $router->post('/event', 'IndexController@store');
        $router->get('/event/{id}', 'IndexController@show');
        $router->put('/event/{id}', 'IndexController@update');
        $router->delete('/event/{id}', 'IndexController@destroy');
    });

    $router->group(["namespace" => "Auth"], function() use ($router) {
        $router->get('/user', 'UserController@index');
        $router->post('/user', 'UserController@store');
        $router->get('/user/{id}', 'UserController@show');
        $router->put('/user/{id}', 'UserController@update');
        $router->delete('/user/{id}', 'UserController@destroy');
    });
});