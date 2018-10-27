<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\V1\Helpers\Helper;
use App\Http\V1\Helpers\FilesHelper as Files;

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
    public function indexAction(Request $request, $id) {
        return view("app", ["test" => $id]);
        if ($request->isMethod('GET')) {
            // resources/views/app.blade.php

        }
    }

    /*
     *
     * @var request
     * @var $version (string) - e. g. 9.5.0
     */
    public function downloadTypo3VersionAction(Request $request, string $version) {
        $file = new Files();
        $file->downloadTypo3Version("http://get.typo3.org", "typo3_src-" . $version, "./../../typo3_sources");
    }

    public function extractTypo3ArchiveAction(Request $request, string $version) {

    }
}
