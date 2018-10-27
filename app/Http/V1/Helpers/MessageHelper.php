<?php
declare(strict_types=1);

namespace App\Http\V1\Helpers;

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

class MessageHelper {
    function __construct() {

    }

    static function message($class, $message) {
        switch ($class) {
            case 'info':
                echo "<span class='info-msg'><i class='fa fa-info-circle'></i>{$message}</span>";
                break;

            case 'success':
                echo "<span class='success-msg'><i class='fa fa-check'></i>{$message}</span>";
                break;

            case 'warning':
                echo "<span class='warning-msg'><i class='fa fa-warning'></i>{$message}</span>";
                break;

            case 'error':
                echo "<span class='error-msg'><i class='fa fa-times-circle'></i>{$message}</span>";
                break;

            default:
                echo "<span class='{$class}'>{$message}</span>";
                break;
        }
    }
}
