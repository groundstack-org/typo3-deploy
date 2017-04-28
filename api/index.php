<?php
require_once("../resources/lib/Helper.php");
require_once("../resources/lib/Deployer.php");
require_once("../resources/lib/Api.php");

$postData = file_get_contents('php://input');
$postData_toArray = json_decode($postData, true);

// print_r($postData_toArray);

$api = new Api($postData_toArray);
