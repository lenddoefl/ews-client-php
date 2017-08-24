<?php
include __DIR__ . '/../ScoresAPIController.php';
use EFLGlobal\EWSPHPClient\ScoresAPIController;
use PHPUnit\Framework\TestCase;

class ScoresAPITests extends TestCase
{
    public function testOnCreateInstanceHasNoTokens()
    {
        $testInstance = new ScoresAPIController('https://uat-external.eflglobal.com/api/v1/scores/',
            'TestKeys/Scores/identifier.txt',
            'TestKeys/Scores/decryption.key',
            'TestKeys/Scores/encryption.key');

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