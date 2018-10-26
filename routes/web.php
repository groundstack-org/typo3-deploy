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

$router->get('/', function() {
    return view('app');
});

$router->group(['prefix' => 'api'], function ($router) {

    $router->group(['prefix' => 'v1'], function ($router) {
        $router->get('/deploy','DeployController@indexAction');

        $router->get('/typo3/{id}','DeployController@getTypo3Action');

        $router->post('/typo3','DeployController@createTypo3Action');

        $router->put('/typo3/{id}','DeployController@updateTypo3Action');

        $router->delete('/typo3/{id}','DeployController@deleteTypo3Action');
    });
});