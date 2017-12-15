<?php
/**
 * Typo3 simple deployer
 * 
 * @package: Typo3 simple deployer
 * @Author:  Christian Hackl 
 * @Date:    2017-12-15 12:29:03 
 * @link:    http://www.hauer-heinrich.de 
 * @license: MIT License
 * @Last     Modified by: Christian Hackl
 * @Last     Modified time: 2017-12-15 13:27:58
 */

require_once "../resources/lib/Helper.php";
require_once "../resources/lib/Deployer.php";
require_once "../resources/lib/Api.php";

$postData = file_get_contents('php://input');
$postData_toArray = json_decode($postData, true);

$api = new Api($postData_toArray);
