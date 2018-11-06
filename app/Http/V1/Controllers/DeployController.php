<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Controllers\Controller;
use Illuminate\Http\Request;
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
            return redirect()->route("app", [
                "message" => Message::message("success", "File uploaded")
            ]);

            return redirect()->route("/")->with("message", "Login failed!");
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

        // return view("app", ["test" => $arguments[0]]);

        if(Test::testDBConnection("db", "root", "root", "localhost")) {
            echo "geht";
        } else {
            echo "geht nicht";
        }
    }
}
