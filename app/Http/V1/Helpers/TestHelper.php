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

use PDO;

class testHelper extends Helper  {
    function __construct() {

    }

    static function testDBConnection(string $dbName, string $user, string $password, $host = false, $port = false) {
        if(!$host) {
            $host = "localhost";
        }

        if(!$port || (!is_string($port) && !is_numeric($port))) {
            $port = "3306";
        }

        try {
            $conn = new PDO("mysql:host={$host}; dbname={$dbName}; port={$port}", $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return true;
        } catch(PDOException $e) {
            return false;
        }
    }
}
