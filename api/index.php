<?php
require_once("../lib/Helper.php");
require_once("../lib/Deployer.php");
require_once("../lib/Api.php");

$data = (file_get_contents('php://input'));
$api = new Api($data);