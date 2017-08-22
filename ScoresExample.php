<?php
require_once 'ScoresController.php';

$requestScores = new EWSPHPClient\ScoresController('https://uat-external.eflglobal.com/api/v1/scores/',
    'TestKeys/Scores/identifier.txt',
    'TestKeys/Scores/decryption.key',
    'TestKeys/Scores/encryption.key');

echo $requestScores->callLogin();
echo '<br><br>';

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


echo $requestScores->callSubject($data);
echo '<br><br>';

$data = "2017-03-14 00:00:00";

echo $requestScores->callDateQuery($data);