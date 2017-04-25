<?php
// require_once("../lib/Helper.php");
// require_once("../lib/Deployer.php");
// require_once("../lib/Api.php");
//
// $api = new Api($_POST);
$data = (file_get_contents('php://input'));
print_r($HTTP_RAW_POST_DATA);
