<?php
declare(strict_types=1);

namespace App\Http\V1\Helpers;

use App\Http\V1\Helpers\MessageHelper as Message;

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

class Helper {

    function __construct() {
    }

    /**
     *
     * return the path to the document root
     * @return (string) document root path
     */
    public function getDocumentRoot() {
        return $_SERVER["DOCUMENT_ROOT"];
    }

    /**
     *
     * return the document domain name
     * @return (string) document domain name
     */
    public function getDocumentDomain() {
        $domain = $_SERVER['HTTP_HOST'];
        if (!isset($domain)) {
            $domain = $_SERVER['SERVER_NAME'];
        }
        return $domain;
    }

    /**
     * [Gets the TYPO3 original json info file]
     *
     * $format bool = get list as html list(ul) or as html select(options)
     */
    public function getTypo3SourcesList(bool $format) {
        if($_SERVER['SERVER_ADDR'] === $_SERVER['REMOTE_ADDR']) {
            $url = 'http://get.typo3.org/json';
            $SSLverify = 0;
        } else {
            $url = 'https://get.typo3.org/json';
            $SLLverify = 1;
        }

        $data = "";

        if(function_exists('curl_version')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $SSLverify);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            $result = curl_exec($ch);

            $sources = json_decode($result, $assoc = true);

            if($format) {
                echo "<select name='typo3_sources' class='select-typo3-source'>";
                foreach ($sources as $version => $releases) {
                    echo "<optgroup label='Major Version: {$version}'>";
                    foreach ($releases as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            echo "<option value='version-{$value1["version"]}'>version: {$value1["version"]}</option>";
                        }
                    }
                    echo "</optgroup>";
                }
                echo "</select>";
            } else {
                echo "<div class='list-container'>";
                foreach ($sources as $version => $releases) {
                    echo "<ul class='list'>";
                    echo "<li><span class='label'>Major Version: {$version}</span><ul>";
                    foreach ($releases as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            echo "<li>version: {$value1["version"]}</li>";
                        }
                    }
                    echo "</ul></li>";
                    echo "</ul>";
                }
                echo "</div>";
            }
        } else {
            return false;
        }
    }
}
