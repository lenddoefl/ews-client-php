<?php
require_once 'AJAPController.php';

$test = new \EWSPHPClient\ApplicantJourneyController($argv[1], $argv[2], $argv[3], $argv[4]);
$data = $data = [
    "applicant"=> new stdClass,
    "application"=>   $argv[5]
];
echo $test->callLogin();
echo $test->callStartSession($data);
