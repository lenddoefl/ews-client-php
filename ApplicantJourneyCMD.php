<?php
require_once 'ApplicantJourneyController.php';

$loginTest = new \EWSPHPClient\ApplicantJourneyController($argv[1], $argv[2], $argv[3], $argv[4]);
echo $loginTest->callLogin();
