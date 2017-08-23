<?php
include __DIR__ . '/../AJAPIController.php';
use EFLGlobal\EWSPHPClient\AJAPIController;
use PHPUnit\Framework\TestCase;

class AJAPITests extends TestCase
{

    public function testOnCreateInstanceHasNoTokens()
    {
        $testInstance = new AJAPIController('https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            'TestKeys/ApplicantJourney/identifier.txt',
            'TestKeys/ApplicantJourney/decryption.key',
            'TestKeys/ApplicantJourney/encryption.key');
        $this->assertAttributeEmpty('reqToken64', $testInstance, 'Attribute reqToken64 must be empty.');
        $this->assertAttributeEmpty('authToken64', $testInstance, 'Attribute authToken64 must be empty.');

        return $testInstance;
    }

    /**
     * @depends testOnCreateInstanceHasNoTokens
     */
    public function testLoginIfReturnsStatusOK($testInstance)
    {

        $response = $testInstance->callLogin();
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");

        return $testInstance;
    }

    /**
     * @depends testLoginIfReturnsStatusOK
     */
    public function testLoginCreatesReqToken($testInstance)
    {
        $this->assertAttributeNotEmpty('reqToken64', $testInstance, "CallLogin method must store reqToken64 attribute.");
        $this->assertAttributeNotEmpty('authToken64', $testInstance, "CallLogin method must store authToken64 attribute.");
    }

    /**
     * @depends testLoginIfReturnsStatusOK
     */
    public function testIfVariablesEmptyBeforeStartSession($testInstance)
    {
        $this->assertAttributeEmpty('applicationHash', $testInstance, "Attribute applicationHash must be empty.");
        $this->assertAttributeEmpty('publicKey', $testInstance, "Attribute publicKey must be empty.");
        $this->assertAttributeEmpty('uid', $testInstance, "Attribute uid must be empty.");
    }
    /**
     * @depends testLoginIfReturnsStatusOK
     */
    public function testSessionStartReturnsStatusOK($testInstance)
    {
        $sessionStartData = [
            "applicant"=> new stdClass(),
            "application"=> "sdkExample"
        ];

        $response = $testInstance->callStartSession($sessionStartData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");

        return $testInstance;
    }

    /**
     * @depends testSessionStartReturnsStatusOK
     */
    public function testSessionStartCreateVariables($testInstance)
    {
        $this->assertAttributeNotEmpty('applicationHash', $testInstance, "CallStartSession method must store applicationHash attribute.");
        $this->assertAttributeNotEmpty('publicKey', $testInstance, "CallStartSession method must store publicKey attribute.");
        $this->assertAttributeNotEmpty('uid', $testInstance, "CallStartSession method must store uid attribute.");
    }

}