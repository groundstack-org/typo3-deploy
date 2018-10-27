<?php
declare(strict_types=1);

namespace App\Http\V1\Helpers;

use App\Http\V1\Helpers\Helper;

/**
 * Typo3 simple deployer
 *
 * @package: Typo3 simple deployer
 * @Author:  Christian Hackl
 * @Date:    2018-10-15 12:29:03
 * @link:    http://www.groundstack.de
 * @license: MIT License
 * @Last     Modified by: Christian Hackl
 * @Last     Modified time: 2018-10-20 13:27:58
 */

class FilesHelper extends Helper {

    function __construct() {
    }

    /*
     * @var $path(string) - Folder path: all folders that do not exist will be created
     * @return (bool) - true or false
     */
    public function createDir(string $path) {
        if(mkdir($path)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * @var $originPath(string) - path to download from e. g. http://get.typo3.org
     * @var $fileName(string) -
     * @var $destinationPath(string) - where to download (path)
     * @return (bool) - true or false
     */
    public function downloadTypo3Version(string $originPath, string $fileName, string $destinationPath) {
        if(!is_Dir($destinationPath)) {
            if(!$this->createDir($destinationPath)) {
                return false;
            }
        }

        if(!@copy($originPath . "/" . $fileName, $destinationPath ."/" . $fileName . ".zip")) {
            $errors= error_get_last();
            MessageHelper::message("error", "COPY ERROR: ".$errors['type']);
            MessageHelper::message("error", $errors['message']);
            return false;
        } else {
            MessageHelper::message("success", "File copied from remote!");
            return true;
        }
    }

    /*
     * @var $pathToZipFile(string) - path to the file which you like to extract
     * @var $pathToExtract(string) - path to the folder where the extracted file is safed
     * @return (bool) - true or false
     */
    public function extractZipFile(string $pathToZipFile, string $pathToExtract) {
        if (file_exists($pathToZipFile)) {
            $phar = new PharData($pathToZipFile);

            if ($phar->extractTo($pathToExtract)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
