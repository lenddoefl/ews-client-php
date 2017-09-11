<?php
require_once __DIR__.'/../ScoresAPIController.php';

if (isset($argv[1])) {
    $file = fopen($argv[1], 'r');
    $file = fread($file, 10485760);
    $arguments = explode(PHP_EOL, $file);
}
else {
    $arguments = ['https://uat-external.eflglobal.com/api/v1/scores/',
        '../TestKeys/Scores/identifier.txt',
        '../TestKeys/Scores/decryption.key',
        '../TestKeys/Scores/encryption.key'];
}

$requestScores = new EFLGlobal\EWSClient\ScoresAPIController($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

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