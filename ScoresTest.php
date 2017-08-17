<?php
require_once 'ScoresController.php';

$loginRequestScores = new EWSPHPClient\ScoresController();
$loginRequestScores->setIdentifier('TestKeys/Scores/identifier.txt');
echo $loginRequestScores->callLogin('https://uat-external.eflglobal.com/api/v1/scores/login.json');