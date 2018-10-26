<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeployController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function indexAction(Request $request) {
        // return "hello";

        // resources/views/app.blade.php
        return view('app', ['user' => "test"]);
    }

    public function getTypo3Action(Request $request, $id) {
        var_dump($id);
    }
}
