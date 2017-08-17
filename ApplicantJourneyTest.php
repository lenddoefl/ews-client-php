<?php
require_once 'ApplicantJourneyController.php';
require_once 'ScoresController.php';

$loginRequestApplicantJourney = new EWSPHPClient\ApplicantJourneyController;
echo $loginRequestApplicantJourney->callLogin('https://uat-external.eflglobal.com/api/v2/applicant_journey/login.json',
    'TestKeys/ApplicantJourney/identifier.txt' );
echo '<br>';
$loginRequestScores = new EWSPHPClient\ScoresController;
echo $loginRequestScores->callLogin('https://uat-external.eflglobal.com/api/v1/scores/login.json',
    'TestKeys/Scores/identifier.txt' );