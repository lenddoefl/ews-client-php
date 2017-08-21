<?php
require_once 'ScoresController.php';

$loginTest = new \EWSPHPClient\ScoresController($argv[1], $argv[2], $argv[3], $argv[4]);
echo $loginTest->callLogin();
