<?php
require_once 'ApplicantJourneyController.php';

$loginRequestApplicantJourney = new EWSPHPClient\ApplicantJourneyController();
$loginRequestApplicantJourney->setIdentifier('TestKeys/ApplicantJourney/identifier.txt');
echo $loginRequestApplicantJourney->callLogin('https://uat-external.eflglobal.com/api/v2/applicant_journey/login.json');

