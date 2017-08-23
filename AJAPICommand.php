<?php
require_once 'AJAPIController.php';

$test = new \EWSPHPClient\AJAPIController($argv[1], $argv[2], $argv[3], $argv[4]);
$data = $data = [
    "applicant"=> new stdClass,
    "application"=>   $argv[5]
];
echo $test->callLogin();
echo $test->callStartSession($data);
