<?php
require_once 'ScoresAPIController.php';

$requestScores = new \EWSPHPClient\ScoresAPIController($argv[1], $argv[2], $argv[3], $argv[4]);
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

echo $requestScores->callSubject($data);