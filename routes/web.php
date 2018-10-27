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

$router->group(['prefix' => 'api','namespace' => '\App\Http'], function($router) {

    $router->group(['prefix' => 'v1','namespace' => 'V1\Controllers'], function($router) {

        $router->get('/deploy','DeployController@indexAction');

        $router->get('/deploy/{id}','DeployController@indexAction');

        $router->get('/download/typo3/{version}','DeployController@downloadTypo3VersionAction');

        $router->post('/extract/{version}','DeployController@extractTypo3ArchiveAction');

        $router->put('/typo3/{id}','DeployController@updateTypo3Action');

        $router->delete('/typo3/{id}','DeployController@deleteTypo3Action');
    });
});
