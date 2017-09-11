<?php
include __DIR__ . '/../ScoresAPIController.php';
use EFLGlobal\EWSClient\ScoresAPIController;
use PHPUnit\Framework\TestCase;

class ScoresAPITests extends TestCase
{
    public function testOnCreateInstanceHasNoTokens()
    {
        global $argv;
        if (isset($argv[2])){
            $file = fopen($argv[2], 'r');
            $file = fread($file, 10485760);
            $arguments = explode(PHP_EOL, $file);
        }
        else {
            $arguments = ['https://uat-external.eflglobal.com/api/v1/scores/',
                'TestKeys/Scores/identifier.txt',
                'TestKeys/Scores/decryption.key',
                'TestKeys/Scores/encryption.key'];
        }
        $testInstance = new ScoresAPIController($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $this->assertAttributeEmpty('reqToken64', $testInstance, 'Attribute reqToken64 must be empty.');
        $this->assertAttributeEmpty('authToken64', $testInstance, 'Attribute authToken64 must be empty.');

        return $testInstance;
    }

    /**
     * @depends testOnCreateInstanceHasNoTokens
     */
    public function testLoginIfReturnsStatusSuccess($testInstance)
    {
        $response = $testInstance->callLogin();
        $response = json_decode($response);
        $this->assertEquals($response->status, 1, "Server must return status 1.");
        $this->assertEquals($response->statusMessage, "Success", "Server must return status message Success.");

        return $testInstance;
    }

    /**
     * @depends testLoginIfReturnsStatusSuccess
     */
    public function testLoginCreatesReqToken($testInstance)
    {
        $this->assertAttributeNotEmpty('reqToken64', $testInstance, "CallLogin method must store reqToken64 attribute.");
        $this->assertAttributeNotEmpty('authToken64', $testInstance, "CallLogin method must store authToken64 attribute.");
    }

    /**
     * @depends testLoginIfReturnsStatusSuccess
     */
    public function testCallSubjectReturnsStatusSuccess($testInstance)
    {
        $subjectData = [
            [
                "identification"=> [
                    [
                        "type"=> "nationalId",
                        "value"=> "DZ-015"
                    ]
                ]
            ]
        ];

        $response = $testInstance->callSubject($subjectData);
        $response = json_decode($response);
        $this->assertEquals($response->status, 1, "Server must return status 1.");
        $this->assertEquals($response->statusMessage, "Success", "Server must return status message Success.");
    }

    /**
     * @depends testLoginIfReturnsStatusSuccess
     */
    public function testCallDateQueryReturnesStatusSuccess($testInstance)
    {
        $dateQueryData = "2017-08-14 00:00:00";

        $response = $testInstance->callDateQuery($dateQueryData);
        $response = json_decode($response);
        $this->assertEquals($response->status, 1, "Server must return status 1.");
        $this->assertEquals($response->statusMessage, "Success", "Server must return status message Success.");
    }

}