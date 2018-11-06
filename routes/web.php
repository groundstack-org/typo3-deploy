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

// $router->get('/', function() {
//     return view('app');
// });

$router->group(['namespace' => '\App\Http\V1\Controllers'], function($group) {
    // id = optional parameter
    // $group->get('/[{id}]', 'DeployController@indexAction');
    $group->get('/', 'DeployController@indexAction');
});

$router->group(['prefix' => 'api','namespace' => '\App\Http'], function($router) {

    $router->group(['prefix' => 'v1','namespace' => 'V1\Controllers'], function($router) {

        $router->get('/download/typo3/{version}','DeployController@downloadTypo3VersionAction');

        $router->post('/extract/{version}','DeployController@extractTypo3ArchiveAction');

        $router->post('/fileupload', 'DeployController@fileUploadAction');

        // $router->post('/fileupload', function (Illuminate\Http\Request $request) {
        //     $file = $request->input('file');
        //     $result = App\Http\V1\Controllers\DeployController::fileUpload($file);
        //     return var_dump($result);
        // });





        $router->put('/typo3/{id}','DeployController@updateTypo3Action');

        $router->delete('/typo3/{id}','DeployController@deleteTypo3Action');

        $router->get("/test/system/{arguments}/{test}", "DeployController@testSystem");

        $router->get("/test/system", "DeployController@testSystem");

    });
});
