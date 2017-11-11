<?php
require_once __DIR__.'/../AJAPIController.php';

if (!isset($argv[2])) {
    $file = fopen($argv[1], 'r');
    $file = fread($file, 10485760);
    $arguments = explode(PHP_EOL, $file);

    $test = new \EFLGlobal\EWSClient\AJAPIController($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
}
else {
    $test = new \EFLGlobal\EWSClient\AJAPIController($argv[1], $argv[2], $argv[3], $argv[4]);
}

$data = $data = [
    "applicant"=> new stdClass,
    "application"=>   (!isset($argv[2])) ? $arguments[4] : $argv[5]
];

echo $test->callStartSession($data);
