<?php
require_once("../lib/Helper.php");
require_once("../lib/Deployer.php");
require_once("../lib/Api.php");

$data = (file_get_contents('php://input'));
$api = new Api($data);

// if ($_SERVER["REQUEST_METHOD"] === "GET") {
//   echo 'IS_GETTED       ';
// }
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//   echo 'IS_POSTED       ';
// }
//
//
// $data = (file_get_contents('php://input'));
// echo("file_get_contents: ".$data);
