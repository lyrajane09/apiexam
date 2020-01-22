<?php

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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->get('/', 'OrderController@index');
$router->group(['prefix' => 'orders'], function () use ($router) {
    $router->post('create',  ['uses' => 'OrderController@create']);
    $router->post('add-item',  ['uses' => 'OrderController@addItem']);
    $router->post('country',  ['uses' => 'OrderController@country']);
});
