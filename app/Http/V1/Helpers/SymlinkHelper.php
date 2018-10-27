<?php
declare(strict_types=1);

namespace App\Http\V1\Helpers;

use App\Http\V1\Helpers\MessageHelper;

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

class SymlinkHelper {
    function __construct() {

    }

    private function createSymlink($target = false, $filename = false) {
        if($target == false || $link == false) {
            echo "<span class='error'>No target: {$target} or link: {$link}</span>";
            return false;
        } else if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // if windows dont create symlinks instead create junction- show info message
            if($this->createWINJunction($target, $filename)) {
                MessageController::message("success",
                    "Windows junction successfully created: {$filename}"
                );
                return true;
            } else {
                MessageController::message("warning",
                    "Please create that symlink/junction manually: {$filename}!"
                );
                MessageController::message("warning",
                    "<a href='https://wiki.typo3.org/Symlinks_on_Windows' title='create symlinks on windows'>Create symlinks on windows.</a>"
                );
                return false;
            }
        } else {
            if (symlink($target, $filename)) {
                MessageController::message("success",
                    "Symlink: {$filename} successfully created."
                );
                return true;
            } else {
                MessageController::message("error",
                    "Symlink: {$filename} not created!"
                );
                return false;
            }
        }
    }

    private function deleteSymlink() {
        echo 'Hello, autoloaded world!';
    }

    private function createWINJunction($target = false, $link = false) {
        if($target == false || $link == false) {
            echo "<span class='error'>No target: {$target} or link: {$link}</span>";
            return false;
        } else if($_SERVER['WINDIR'] || $_SERVER['windir']) {
            if(is_dir($target)) {
                exec('mklink /j "' . str_replace('/', '\\', $link) . '" "' .str_replace('/', '\\', $target) . '"');
            } else {
                exec('mklink "' . str_replace('/', '\\', $link) . '" "' .str_replace('/', '\\', $target) . '"');
            }
            echo "<span class='success'></span>";
            return true;
        } else {
            echo "<span class='error'>Can't create Junction - No windows OS?</span>";
            return false;
        }
    }

    private function deleteWINJunction() {
        echo 'Hello, autoloaded world!';
    }
}
