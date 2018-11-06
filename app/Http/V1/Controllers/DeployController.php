<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\V1\Helpers\ValidateHelper as Validate;
use App\Http\V1\Helpers\MessageHelper as Message;
use App\Http\V1\Helpers\Helper;
use App\Http\V1\Helpers\FilesHelper as Files;
use App\Http\V1\Helpers\TestHelper as Test;

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

    /*
     * @param  Request  $request
     * @param  string  $test
     * @return Response
     */
    public function indexAction(Request $request, $id = false) {
        if ($request->isMethod('GET')) {
            // resources/views/app.blade.php
            return view("app", ["test" => $id]);
        }
    }

    /*
     *
     * @var request
     * @var $version (string) - e. g. 9.5.0
     */
    public function downloadTypo3VersionAction(Request $request, string $version) {
        $url = "http://get.typo3.org";
        $fileName = "typo3_src-" . $version;
        $path = "./../../typo3_sources";

        $file = new Files();
        $download = $file->downloadTypo3Version($url, $filename, $path);
        if ($request->isMethod('GET')) {
            if($download) {
                return view("app", [
                    "message" => "Downloaded: {$filename} to {$path}"
                ]);
            } else {
                return view("app", [
                    "message" => "test"
                ]);
            }
        } else if($request->isMethod('POST')) {
            if($download) {
                Message::message("success", "Downloaded: {$filename} to {$path}");
            } else {
                foreach($download as $error) {
                    var_dump($error);
                    Message::message("error", "test");
                }
            }
        }
    }

    public function extractTypo3ArchiveAction(Request $request, string $version) {

    }

    public function fileUploadAction(Request $request) {
        $destinationPath = "./../../typo3_sources";
        $file = $request->file('file');
        $targetFileName = "typo3.png";
        $allowedFileType = "image/png";

        if(Files::fileUpload($request, "file", $destinationPath, $targetFileName, $allowedFileType)) {
            return view("app", [
                "message" => Message::message("success", "File uploaded!")
            ]);
        } else {
            return view("app", [
                "message" => Message::message("error", "File not uploaded!")
            ]);
        }
    }

    public function testSystem(...$arguments) {
        foreach($arguments as $arg) {
            echo $arg;
        }

        $maxExecutionTime = ini_get('max_execution_time');
        if ($maxExecutionTime < 240) {
            echo "<p class='warning'>max_execution_time = {$maxExecutionTime} <span>(should be 240!)</span></p>";
        } else {
            echo "<p class='success'>max_execution_time = {$maxExecutionTime}</p>";
        }

        $memory_limit = ini_get('memory_limit');
        if ($memory_limit < 128) {
            echo "<p class='warning'>memory_limit = {$memory_limit} <span>(should be 128!)</span></p>";
        } else {
            echo "<p class='success'>memory_limit = {$memory_limit}</p>";
        }

        $max_input_vars = ini_get('max_input_vars');
        if ($max_input_vars < 1500) {
            echo "<p class='warning'>max_input_vars = {$max_input_vars} <span>(should be 1500!)</span></p>";
        } else {
            echo "<p class='success'>max_input_vars = {$max_input_vars}</p>";
        }

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            echo "<p class='warning'>Warning: it seems like this script is running under windows. Symlinks may not work! More info: <a href='https://wiki.typo3.org/Symlinks_on_Windows' title='create symlinks on windows'>Create symlinks on windows.</a></span></p>";
        }
    }

    /**
     *
     */
    public function testDB(Request $request) {
        if ($request->isMethod('post')) {
            $dbname = $request->input('dbname');
            $dbuser = $request->input('dbuser');
            $dbpassword = Validate::valid_password($request->input('dbpassword'));
            $dbhost = $request->input('dbhost');
            $dbport = $request->input('dbport');

            $connection = Test::testDBConnection($dbname, $dbuser, $dbpassword, $dbhost, $dbport);

            if($connection === true) {
                return view("app", [
                    "message" => Message::message("success", "Database connection established.")
                ]);
            } else {
                $error = $connection->getMessage();
                return view("app", [
                    "message" => Message::message("error", "Database connection not established!"),
                    "messageinfo" => Message::message("error", $error)
                ]);
            }
        } else {
            return view("app", [
                "message" => Message::message("error", "only POST allowed!")
            ]);
        }
    }
}
