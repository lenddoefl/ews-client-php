<?php
require_once 'ScoresController.php';

$requestScores = new \EWSPHPClient\ScoresController($argv[1], $argv[2], $argv[3], $argv[4]);
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