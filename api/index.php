<?php
require_once("../resources/lib/Helper.php");
require_once("../resources/lib/Deployer.php");
require_once("../resources/lib/Api.php");

$data = (file_get_contents('php://input'));
$api = new Api($data);
