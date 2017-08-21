<?php
require_once 'ScoresController.php';

$requestScores = new EWSPHPClient\ScoresController('https://uat-external.eflglobal.com/api/v1/scores/',
    'TestKeys/Scores/identifier.txt',
    'TestKeys/ApplicantJourney/decryption.key',
    'TestKeys/ApplicantJourney/encryption.key');
echo $requestScores->callLogin();

$data = ["2017-03-14 00:00:00"];

$requestScores->callDateQuery($data);