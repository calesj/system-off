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

$router->group(['prefix' => 'moto'], function () use ($router) {
    $router->get('', 'MotoController@index');
    $router->get('{id}', 'MotoController@show');
    $router->post('', 'MotoController@store');
    $router->post('{id}', 'MotoController@update');
    $router->delete('{id}', 'MotoController@destroy');
});

$router->group(['prefix' => 'carro'], function () use ($router) {
    $router->get('', 'CarroController@index');
    $router->get('{id}', 'CarroController@show');
    $router->post('', 'CarroController@store');
    $router->post('{id}', 'CarroController@update');
    $router->delete('{id}', 'CarroController@destroy');
});
