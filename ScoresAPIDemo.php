<?php
require_once 'ScoresAPIController.php';

$requestScores = new EWSPHPClient\ScoresAPIController('https://uat-external.eflglobal.com/api/v1/scores/',
    'TestKeys/Scores/identifier.txt',
    'TestKeys/Scores/decryption.key',
    'TestKeys/Scores/encryption.key');

echo "CallLogin method returns: <br>";
echo $requestScores->callLogin();

$data = [
    [
        "identification"=> [
            [
                "type"=> "nationalId",
                "value"=> "DZ-015"
            ]
        ]
    ]
];

echo "<br><br>CallSubject method returns: <br>";
echo $requestScores->callSubject($data);


$data = "2017-08-14 00:00:00";

echo "<br><br>CallDateQuery method returns: <br>";
echo $requestScores->callDateQuery($data);