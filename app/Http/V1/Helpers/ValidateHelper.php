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

class ValidateHelper {
    function __construct() {

    }

    static function valid_password(string $password) {
        // allowed: a - z, A - Z, 0 - 9, ?!#%$@-_
        $re = '/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,200}$/m';

        if(preg_match_all($re, $password, $matches, PREG_SET_ORDER, 0)) {
            return true;
        } else {
            return false;
        }
    }
}
