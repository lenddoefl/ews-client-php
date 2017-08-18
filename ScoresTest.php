<?php
require_once 'ScoresController.php';

$loginRequestScores = new EWSPHPClient\ScoresController('https://uat-external.eflglobal.com/api/v1/scores/',
    'TestKeys/Scores/identifier.txt',
    'TestKeys/ApplicantJourney/decryption.key',
    'TestKeys/ApplicantJourney/encryption.key');
echo $loginRequestScores->callLogin();