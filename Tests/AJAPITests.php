<?php

include_once  __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "AJAPIController.php";
require_once __DIR__ . "/ChildClasses/AJAPIChildInt.php";
use EFLGlobal\EWSClient\AJAPIController;
use PHPUnit\Framework\TestCase;

class AJAPITests extends TestCase
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
            $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
                'TestKeys/ApplicantJourney/identifier.txt',
                'TestKeys/ApplicantJourney/decryption.key',
                'TestKeys/ApplicantJourney/encryption.key'];
        }
        $testInstance = new AJAPIController($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
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
    public function testSessionStartCreatesVariables($testInstance)
    {
        $this->assertAttributeNotEmpty('applicationHash', $testInstance, "CallStartSession method must store applicationHash attribute.");
        $this->assertAttributeNotEmpty('publicKey', $testInstance, "CallStartSession method must store publicKey attribute.");
        $this->assertAttributeNotEmpty('uid', $testInstance, "CallStartSession method must store uid attribute.");
    }

    /**
     * @depends testSessionStartReturnsStatusOK
     */
    public function testCallResumeSessionReturnsStatusOK($testInstance)
    {
        $sessionResumeData = [
            "applicant"=> new stdClass()
        ];

        $testInstance->callLogin();
        $response = $testInstance->callResumeSession($sessionResumeData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");

        return [$testInstance, $response->data->uid, $response->data->publicKey];
    }

    /**
     * @depends testCallResumeSessionReturnsStatusOK
     */
    public function testCallResumeSessionStoresNotEmptyAttributes($return)
    {
        $this->assertAttributeNotEmpty('publicKey', $return[0], "CallResumeSession method must store not empty publicKey attribute.");
        $this->assertAttributeNotEmpty('uid', $return[0], "CallResumeSession method must store noe empty uid attribute.");
    }

    /**
     * @depends testCallResumeSessionReturnsStatusOK
     */
    public function testCallResumeSessionStoresOwnAttributes($return)
    {
        $this->assertAttributeEquals($return[2],'publicKey', $return[0], "CallResumeSession stores publicKey variable that is not equal to server response.");
        $this->assertAttributeEquals($return[1],'uid', $return[0], "CallResumeSession stores uid variable that is not equal to server response.");
    }

    /**
     * @depends testCallResumeSessionReturnsStatusOK
     */
    public function testCallGetApplicationReturnsStatusOK($return)
    {
        $testInstance = $return[0];
        $getApplicationData = [
            "device"=> [
                "browser"=> null,
                "deviceId"=> null,
                "ipAddress"=> null,
                "os"=> [
                    "type"=> null,
                    "version"=> null,
                ],
                "referrer"=> null,

                "viewport"=> [
                    "height"=> null,
                    "width"=> null
                ]
            ],
            "player"=> [
                "type"=>    "web-embedded",
                "version"=> "1.20"
            ]
        ];

        $response = $testInstance->callGetApplication($getApplicationData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");

        return [$testInstance, $response->data->applicationHash];
    }

    /**
     * @depends testCallGetApplicationReturnsStatusOK
     */
    public function testCallFinishStepReturnsStatusOK($return)
    {
        $testInstance = $return[0];
        $finishStepData = [
            "applicant"=>    [
                "birthday"=>         '11.11.11',
                "email"=>            'test@test.test',
                "employmentStatus"=> 'intern',
                "firstName"=>       'bob',
                "gender"=>           'm',
                "lastName"=>         'dylan',
                "maritalStatus"=>    'single',
                "loan"=>            [
                    "amount"=>         15000,
                    "businessIncome"=> 15000,
                    "currency"=>       'PEN',
                    "personalIncome"=> 12000,
                    "term"=>           2.17
                ],
                "locale"=>           'en',
                "utcOffset"=>        '2',

                "addresses"=> [
                    "business"=> [
                        "city"=>       'asdf',
                        "country"=>    'USA',
                        "latitude"=>   12,
                        "longitude"=>  12,
                        "street"=>     'qwe',
                        "postalCode"=> 'qwe',
                        "region"=>     'qwer'
                    ],
                    "home"=>     [
                        "city"=>       'asdf',
                        "country"=>    'USA',
                        "latitude"=>   12,
                        "longitude"=>  12,
                        "street"=>     'qwe',
                        "postalCode"=> 'qwe',
                        "region"=>     'qwer'
                    ],
                    "work"=>     [
                        "city"=>       'asdf',
                        "country"=>    'USA',
                        "latitude"=>   12,
                        "longitude"=>  12,
                        "street"=>     'qwe',
                        "postalCode"=> 'qwe',
                        "region"=>     'qwer'
                    ],
                ],

                "connections"=> [
                    "facebook"=>   true,
                    "google"=>     true,
                    "linkedin"=>   true,
                    "microsoft"=>  true,
                    "twitter"=>    true,
                    "yahoo"=>      true
                ],

                "idNumbers"=> [
                    "analyticsId"=>        '123',
                    "bankAccountNumber"=>  '1234123413414',
                    "driversLicense"=>     '1234',
                    "externalKey"=>        '12341234',
                    "nationalId"=>         '1234123',
                    "passport"=>           '12341234134',
                    "phoneNumber"=>        '12341234',
                    "voterId"=>            '1234124'
                ]
            ],
            "device"=>       [
                "browser"=> null,
                "deviceId"=> null,
                "ipAddress"=> null,
                "os"=> [
                    "type"=> null,
                    "version"=> null,
                ],
                "referrer"=> null,

                "viewport"=> [
                    "height"=> null,
                    "width"=> null
                ]
            ],
            "metas"=>        new stdClass,
            "observations"=> new stdClass,
            "state"=>        new stdClass,
            "step"=>         'abGlobal',
        ];

        $response = $testInstance->callFinishStep($finishStepData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");

        return $testInstance;
    }

    /**
     * @depends testCallFinishStepReturnsStatusOK
     */
    public function testCallFinishStepIncreaseSequence($testInstance)
    {
        $this->assertAttributeEquals(1, 'sequence', $testInstance, "CallFinishStep doesn't increase sequence attribute.");
    }

    /**
     * @depends testCallFinishStepReturnsStatusOK
     */
    public function testCallCreateAttachmentReturnsStatusOK($testInstance)
    {
        $createAttachmentData = [
            "attachmentType"=>         'photo',
            "attachmentTypeVersion"=>  '1.0',
            "contentType"=>            'image/jpeg',
            "inlineData"=>             "/9j/4AAQSkZJRgABAQEASABIAAD//gATQ3JlYXRlZCB3aXRoIEdJTVD/2wBDAAEBAQEBAQEBAQEBAQEB
            AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEB
            AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wgARCAAoACgDAREAAhEBAxEB/8QAGgAAAgMBAQAAAAAAAAAAAAAABgkD
            CAoABf/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAU7gQEAYjUhOJ5QflxR/hkYDMs2aDC4ZiNLSj9y6xKZNh2ow4mOP/8QA
            HhAAAQUBAAMBAAAAAAAAAAAAAgEDBAUGBwARExD/2gAIAQEAAQUCl2DryqSmbRkCVkl/7c6o5Nwsv0PkcVIo8NXSxOCfs5HPsUzTRPkT5x6/
            0mJycq2m4DBRqmIACAx2CUsdl5VxL53zeHTRhEQHzJ5B6wc5pztqtAAQA/P/xAAUEQEAAAAAAAAAAAAAAAAAAABA/9oACAEDAQE/AU//xAAU
            EQEAAAAAAAAAAAAAAAAAAABA/9oACAECAQE/AU//xAAoEAABAwIFBAEFAAAAAAAAAAABAAIDESEQEhMiMQRBYfCxMkJx4fH/2gAIAQEABj8C
            c5zjc/KurJmWtaj5UGowlu1UGAAChOkTuHZRF0YD8ra28YCgUTRC5wzD7VDJLEM+Vp48e+8hrRQBbR399sootMnM4D6f0oeomiGpQHj+eUGt
            FAMIxpk1I7c8KLqJod1jdv5TWCwaKY//xAAkEAEAAgIBAgcBAQAAAAAAAAABESExQQBRYRBxgZGhwfDhsf/aAAgBAQABPyF9ZmZdrzCZ3p1E
            hMOqc5Nbx9ctCF/Dq5986jgvK1cp2PSs+nWw1FU1N6l+COI5bCdpiKjy+K4P9/4V/fiZp01+v1chPfkZMyy4Ra+n1d8Hrkw9U4L+8TMpEtk5
            0E9+n+PuN6Xp0lvC/FdNcU5dyIHuXO+k9uGgXqS4UUYKXdmL4BAAAO3ASisK1M/ocNHDUfJEuIL0GWptGJpOHN3WBmYorgMkXw0AoA8IS4bp
            LSiWNYYF6IJAHSnzE/pvhYgACivH/9oADAMBAAIAAwAAABAAAQAACQASCCAf/8QAFBEBAAAAAAAAAAAAAAAAAAAAQP/aAAgBAwEBPxBP/8QA
            FBEBAAAAAAAAAAAAAAAAAAAAQP/aAAgBAgEBPxBP/8QAHRABAQEBAQEAAwEAAAAAAAAAAREhMQBBEFGh8P/aAAgBAQABPxB+MmZqVFUzAgYC
            l4KnVmdUfpSAZYpXWHleCAAQBon1gsWCxJpY7Q1ALwVAIhJBAeFq2664xVSAqgGgMPCQUIFT/QBVVTGEPVQVVSmsaUUVCSV+mPHT2XqVjO3Y
            MFVDUFe3UsmpZR0MswGnqHnAbQI4KFMA2PBViMVaQr8ukOqHd8qLOsCmhihQVe5Sr5eNb0gkcgVTvwBynfJQGCWgQA0gp8gGciYIfz/d9BuA
            hUNKU1AU0CLgrwvxREEKEVIWgPF6kCQXggzAMUgJhlDAAEOfjKkDCLTiY3QcAPFiHjYCpodxydCgEjLcAAAAAGcCHPz/AP/Z",
            "name"=>                   'test',
            "sha1Hash"=>               "91e408d7897162c9f0946aab6bc4a066d75ae6ea",
            "size"=>                   1266,
        ];

        $response = $testInstance->callCreateAttachment($createAttachmentData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");

        return [$testInstance, $response->data->attachmentUid];
    }

    /**
     * @depends testCallCreateAttachmentReturnsStatusOK
     */
    public function testCallCreateAttachmentPushesUid($return)
    {
        $this->assertAttributeContains($return[1], 'attachmentUids', $return[0], "CallCreateAttachment doesn't store attachment uid.");
    }

    /**
     * @depends testCallCreateAttachmentReturnsStatusOK
     */
    public function testCallFinishSessionReturnsStatusOK($return)
    {
        $testInstance = $return[0];

        $response = $testInstance->callFinishSession();
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");
    }



    public function testNoSessionCallPrefetchAttachment()
    {
        global $argv;
        if (isset($argv[2])){
            $file = fopen($argv[2], 'r');
            $file = fread($file, 10485760);
            $arguments = explode(PHP_EOL, $file);
        }
        else {
            $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
                'TestKeys/ApplicantJourney/identifier.txt',
                'TestKeys/ApplicantJourney/decryption.key',
                'TestKeys/ApplicantJourney/encryption.key'];
        }
        $testInstance = new AJAPIController($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $prefetchApplicationData = ["applications" => ["sdkExample"=>   "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0"]];

        $testInstance->callLogin();
        $response = $testInstance->callPrefetchApplications($prefetchApplicationData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");
    }

    public function testStandaloneStartSession()
    {
        global $argv;
        if (isset($argv[2])){
            $file = fopen($argv[2], 'r');
            $file = fread($file, 10485760);
            $arguments = explode(PHP_EOL, $file);
        }
        else {
            $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
                'TestKeys/ApplicantJourney/identifier.txt',
                'TestKeys/ApplicantJourney/decryption.key',
                'TestKeys/ApplicantJourney/encryption.key'];
        }
        $testInstance = new AJAPIController($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $sessionStartData = [
            "applicant"=> new stdClass(),
            "application"=> "sdkExample"
        ];

        $testInstance->callLogin();
        $response = $testInstance->callStartSession($sessionStartData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");

        return $testInstance;
    }

    /**
     * @depends testStandaloneStartSession
     */
    public function testStandaloneCallFinishSession($testInstance)
    {
        $response = $testInstance->callFinishSession();
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");
    }

    /**
     * @depends testStandaloneStartSession
     */
    public function testStandaloneCallFinishStep($testInstance)
    {
        $finishStepData = [
            "applicant"=>    [
                "birthday"=>         '11.11.11',
                "email"=>            'test@test.test',
                "employmentStatus"=> 'intern',
                "firstName"=>       'bob',
                "gender"=>           'm',
                "lastName"=>         'dylan',
                "maritalStatus"=>    'single',
                "loan"=>            [
                    "amount"=>         15000,
                    "businessIncome"=> 15000,
                    "currency"=>       'PEN',
                    "personalIncome"=> 12000,
                    "term"=>           2.17
                ],
                "locale"=>           'en',
                "utcOffset"=>        '2',

                "addresses"=> [
                    "business"=> [
                        "city"=>       'asdf',
                        "country"=>    'USA',
                        "latitude"=>   12,
                        "longitude"=>  12,
                        "street"=>     'qwe',
                        "postalCode"=> 'qwe',
                        "region"=>     'qwer'
                    ],
                    "home"=>     [
                        "city"=>       'asdf',
                        "country"=>    'USA',
                        "latitude"=>   12,
                        "longitude"=>  12,
                        "street"=>     'qwe',
                        "postalCode"=> 'qwe',
                        "region"=>     'qwer'
                    ],
                    "work"=>     [
                        "city"=>       'asdf',
                        "country"=>    'USA',
                        "latitude"=>   12,
                        "longitude"=>  12,
                        "street"=>     'qwe',
                        "postalCode"=> 'qwe',
                        "region"=>     'qwer'
                    ],
                ],

                "connections"=> [
                    "facebook"=>   true,
                    "google"=>     true,
                    "linkedin"=>   true,
                    "microsoft"=>  true,
                    "twitter"=>    true,
                    "yahoo"=>      true
                ],

                "idNumbers"=> [
                    "analyticsId"=>        '123',
                    "bankAccountNumber"=>  '1234123413414',
                    "driversLicense"=>     '1234',
                    "externalKey"=>        '12341234',
                    "nationalId"=>         '1234123',
                    "passport"=>           '12341234134',
                    "phoneNumber"=>        '12341234',
                    "voterId"=>            '1234124'
                ]
            ],
            "device"=>       [
                "browser"=> null,
                "deviceId"=> null,
                "ipAddress"=> null,
                "os"=> [
                    "type"=> null,
                    "version"=> null,
                ],
                "referrer"=> null,

                "viewport"=> [
                    "height"=> null,
                    "width"=> null
                ]
            ],
            "metas"=>        new stdClass,
            "observations"=> new stdClass,
            "state"=>        new stdClass,
            "step"=>         'abGlobal',
        ];

        $response = $testInstance->callFinishStep($finishStepData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");

    }

    /**
     * @depends testStandaloneStartSession
     */
    public function testStandaloneCallGetApplication($testInstance)
    {
        $getApplicationData = [
            "device"=> [
                "browser"=> null,
                "deviceId"=> null,
                "ipAddress"=> null,
                "os"=> [
                    "type"=> null,
                    "version"=> null,
                ],
                "referrer"=> null,

                "viewport"=> [
                    "height"=> null,
                    "width"=> null
                ]
            ],
            "player"=> [
                "type"=>    "web-embedded",
                "version"=> "1.20"
            ]
        ];

        $response = $testInstance->callGetApplication($getApplicationData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");
    }

    /**
     * @depends testStandaloneStartSession
     */
    public function testStandaloneCallCreateAttachment($testInstance)
    {
        $createAttachmentData = [
            "attachmentType"=>         'photo',
            "attachmentTypeVersion"=>  '1.0',
            "contentType"=>            'image/jpeg',
            "inlineData"=>             "/9j/4AAQSkZJRgABAQEASABIAAD//gATQ3JlYXRlZCB3aXRoIEdJTVD/2wBDAAEBAQEBAQEBAQEBAQEB
            AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEB
            AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wgARCAAoACgDAREAAhEBAxEB/8QAGgAAAgMBAQAAAAAAAAAAAAAABgkD
            CAoABf/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAU7gQEAYjUhOJ5QflxR/hkYDMs2aDC4ZiNLSj9y6xKZNh2ow4mOP/8QA
            HhAAAQUBAAMBAAAAAAAAAAAAAgEDBAUGBwARExD/2gAIAQEAAQUCl2DryqSmbRkCVkl/7c6o5Nwsv0PkcVIo8NXSxOCfs5HPsUzTRPkT5x6/
            0mJycq2m4DBRqmIACAx2CUsdl5VxL53zeHTRhEQHzJ5B6wc5pztqtAAQA/P/xAAUEQEAAAAAAAAAAAAAAAAAAABA/9oACAEDAQE/AU//xAAU
            EQEAAAAAAAAAAAAAAAAAAABA/9oACAECAQE/AU//xAAoEAABAwIFBAEFAAAAAAAAAAABAAIDESEQEhMiMQRBYfCxMkJx4fH/2gAIAQEABj8C
            c5zjc/KurJmWtaj5UGowlu1UGAAChOkTuHZRF0YD8ra28YCgUTRC5wzD7VDJLEM+Vp48e+8hrRQBbR399sootMnM4D6f0oeomiGpQHj+eUGt
            FAMIxpk1I7c8KLqJod1jdv5TWCwaKY//xAAkEAEAAgIBAgcBAQAAAAAAAAABESExQQBRYRBxgZGhwfDhsf/aAAgBAQABPyF9ZmZdrzCZ3p1E
            hMOqc5Nbx9ctCF/Dq5986jgvK1cp2PSs+nWw1FU1N6l+COI5bCdpiKjy+K4P9/4V/fiZp01+v1chPfkZMyy4Ra+n1d8Hrkw9U4L+8TMpEtk5
            0E9+n+PuN6Xp0lvC/FdNcU5dyIHuXO+k9uGgXqS4UUYKXdmL4BAAAO3ASisK1M/ocNHDUfJEuIL0GWptGJpOHN3WBmYorgMkXw0AoA8IS4bp
            LSiWNYYF6IJAHSnzE/pvhYgACivH/9oADAMBAAIAAwAAABAAAQAACQASCCAf/8QAFBEBAAAAAAAAAAAAAAAAAAAAQP/aAAgBAwEBPxBP/8QA
            FBEBAAAAAAAAAAAAAAAAAAAAQP/aAAgBAgEBPxBP/8QAHRABAQEBAQEAAwEAAAAAAAAAAREhMQBBEFGh8P/aAAgBAQABPxB+MmZqVFUzAgYC
            l4KnVmdUfpSAZYpXWHleCAAQBon1gsWCxJpY7Q1ALwVAIhJBAeFq2664xVSAqgGgMPCQUIFT/QBVVTGEPVQVVSmsaUUVCSV+mPHT2XqVjO3Y
            MFVDUFe3UsmpZR0MswGnqHnAbQI4KFMA2PBViMVaQr8ukOqHd8qLOsCmhihQVe5Sr5eNb0gkcgVTvwBynfJQGCWgQA0gp8gGciYIfz/d9BuA
            hUNKU1AU0CLgrwvxREEKEVIWgPF6kCQXggzAMUgJhlDAAEOfjKkDCLTiY3QcAPFiHjYCpodxydCgEjLcAAAAAGcCHPz/AP/Z",
            "name"=>                   'test',
            "sha1Hash"=>               "91e408d7897162c9f0946aab6bc4a066d75ae6ea",
            "size"=>                   1266,
        ];

        $response = $testInstance->callCreateAttachment($createAttachmentData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");
    }

    /**
     * @depends testStandaloneStartSession
     */
    public function testStandaloneCallResumeSession($testInstance)
    {
        $sessionResumeData = [
            "applicant"=> new stdClass()
        ];

        $testInstance->callLogin();
        $response = $testInstance->callResumeSession($sessionResumeData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");
    }

    public function testStandaloneStartSessionWithoutManualLogin()
    {
        global $argv;
        if (isset($argv[2])){
            $file = fopen($argv[2], 'r');
            $file = fread($file, 10485760);
            $arguments = explode(PHP_EOL, $file);
        }
        else {
            $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
                'TestKeys/ApplicantJourney/identifier.txt',
                'TestKeys/ApplicantJourney/decryption.key',
                'TestKeys/ApplicantJourney/encryption.key'];
        }
        $testInstance = new AJAPIController($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

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
     * @depends testStandaloneStartSessionWithoutManualLogin
     */

    public function testResumeSessionWithoutManualLogin($testInstance){
        $sessionResumeData = [
            "applicant"=> new stdClass()
        ];
        $response = $testInstance->callResumeSession($sessionResumeData)->getCode();
        $this->assertEquals(403, $response, "Server must return status code 403.");
    }

    public function testNoSessionCallPrefetchAttachmentWithoutManualLogin()
    {
        global $argv;
        if (isset($argv[2])){
            $file = fopen($argv[2], 'r');
            $file = fread($file, 10485760);
            $arguments = explode(PHP_EOL, $file);
        }
        else {
            $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
                'TestKeys/ApplicantJourney/identifier.txt',
                'TestKeys/ApplicantJourney/decryption.key',
                'TestKeys/ApplicantJourney/encryption.key'];
        }
        $testInstance = new AJAPIController($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $prefetchApplicationData = ["applications" => ["sdkExample"=>   "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0"]];

        $response = $testInstance->callPrefetchApplications($prefetchApplicationData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");
    }

    /**
     * These four tests are testing if functions start callLogin and callResumeSession automatically.
     */

    public function testCallCreateAttachmentOn403()
    {
        global $argv;
        if (isset($argv[2])){
            $file = fopen($argv[2], 'r');
            $file = fread($file, 10485760);
            $arguments = explode(PHP_EOL, $file);
        }
        else {
            $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
                'TestKeys/ApplicantJourney/identifier.txt',
                'TestKeys/ApplicantJourney/decryption.key',
                'TestKeys/ApplicantJourney/encryption.key'];
        }
        $testInstance = new AJAPIChildInt($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $sessionStartData = [
            "applicant"=> new stdClass(),
            "application"=> "sdkExample"
        ];
        $testInstance->callStartSession($sessionStartData);

        $testInstance->setAuthToken64(null);
        $testInstance->setReqToken64(null);

        $createAttachmentData = [
            "attachmentType"=>         'photo',
            "attachmentTypeVersion"=>  '1.0',
            "contentType"=>            'image/jpeg',
            "inlineData"=>             "/9j/4AAQSkZJRgABAQEASABIAAD//gATQ3JlYXRlZCB3aXRoIEdJTVD/2wBDAAEBAQEBAQEBAQEBAQEB
            AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEB
            AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wgARCAAoACgDAREAAhEBAxEB/8QAGgAAAgMBAQAAAAAAAAAAAAAABgkD
            CAoABf/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAU7gQEAYjUhOJ5QflxR/hkYDMs2aDC4ZiNLSj9y6xKZNh2ow4mOP/8QA
            HhAAAQUBAAMBAAAAAAAAAAAAAgEDBAUGBwARExD/2gAIAQEAAQUCl2DryqSmbRkCVkl/7c6o5Nwsv0PkcVIo8NXSxOCfs5HPsUzTRPkT5x6/
            0mJycq2m4DBRqmIACAx2CUsdl5VxL53zeHTRhEQHzJ5B6wc5pztqtAAQA/P/xAAUEQEAAAAAAAAAAAAAAAAAAABA/9oACAEDAQE/AU//xAAU
            EQEAAAAAAAAAAAAAAAAAAABA/9oACAECAQE/AU//xAAoEAABAwIFBAEFAAAAAAAAAAABAAIDESEQEhMiMQRBYfCxMkJx4fH/2gAIAQEABj8C
            c5zjc/KurJmWtaj5UGowlu1UGAAChOkTuHZRF0YD8ra28YCgUTRC5wzD7VDJLEM+Vp48e+8hrRQBbR399sootMnM4D6f0oeomiGpQHj+eUGt
            FAMIxpk1I7c8KLqJod1jdv5TWCwaKY//xAAkEAEAAgIBAgcBAQAAAAAAAAABESExQQBRYRBxgZGhwfDhsf/aAAgBAQABPyF9ZmZdrzCZ3p1E
            hMOqc5Nbx9ctCF/Dq5986jgvK1cp2PSs+nWw1FU1N6l+COI5bCdpiKjy+K4P9/4V/fiZp01+v1chPfkZMyy4Ra+n1d8Hrkw9U4L+8TMpEtk5
            0E9+n+PuN6Xp0lvC/FdNcU5dyIHuXO+k9uGgXqS4UUYKXdmL4BAAAO3ASisK1M/ocNHDUfJEuIL0GWptGJpOHN3WBmYorgMkXw0AoA8IS4bp
            LSiWNYYF6IJAHSnzE/pvhYgACivH/9oADAMBAAIAAwAAABAAAQAACQASCCAf/8QAFBEBAAAAAAAAAAAAAAAAAAAAQP/aAAgBAwEBPxBP/8QA
            FBEBAAAAAAAAAAAAAAAAAAAAQP/aAAgBAgEBPxBP/8QAHRABAQEBAQEAAwEAAAAAAAAAAREhMQBBEFGh8P/aAAgBAQABPxB+MmZqVFUzAgYC
            l4KnVmdUfpSAZYpXWHleCAAQBon1gsWCxJpY7Q1ALwVAIhJBAeFq2664xVSAqgGgMPCQUIFT/QBVVTGEPVQVVSmsaUUVCSV+mPHT2XqVjO3Y
            MFVDUFe3UsmpZR0MswGnqHnAbQI4KFMA2PBViMVaQr8ukOqHd8qLOsCmhihQVe5Sr5eNb0gkcgVTvwBynfJQGCWgQA0gp8gGciYIfz/d9BuA
            hUNKU1AU0CLgrwvxREEKEVIWgPF6kCQXggzAMUgJhlDAAEOfjKkDCLTiY3QcAPFiHjYCpodxydCgEjLcAAAAAGcCHPz/AP/Z",
            "name"=>                   'test',
            "sha1Hash"=>               "91e408d7897162c9f0946aab6bc4a066d75ae6ea",
            "size"=>                   1266,
        ];

        $response = $testInstance->callCreateAttachment($createAttachmentData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");
    }

    public function testCallFinishSessionOn403()
    {
        global $argv;
        if (isset($argv[2])){
            $file = fopen($argv[2], 'r');
            $file = fread($file, 10485760);
            $arguments = explode(PHP_EOL, $file);
        }
        else {
            $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
                'TestKeys/ApplicantJourney/identifier.txt',
                'TestKeys/ApplicantJourney/decryption.key',
                'TestKeys/ApplicantJourney/encryption.key'];
        }
        $testInstance = new AJAPIChildInt($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $sessionStartData = [
            "applicant"=> new stdClass(),
            "application"=> "sdkExample"
        ];
        $testInstance->callStartSession($sessionStartData);

        $testInstance->setAuthToken64(null);
        $testInstance->setReqToken64(null);

        $response = $testInstance->callFinishSession();
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");
    }

    public function testCallFinishStepOn403()
    {
        global $argv;
        if (isset($argv[2])){
            $file = fopen($argv[2], 'r');
            $file = fread($file, 10485760);
            $arguments = explode(PHP_EOL, $file);
        }
        else {
            $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
                'TestKeys/ApplicantJourney/identifier.txt',
                'TestKeys/ApplicantJourney/decryption.key',
                'TestKeys/ApplicantJourney/encryption.key'];
        }
        $testInstance = new AJAPIChildInt($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $sessionStartData = [
            "applicant"=> new stdClass(),
            "application"=> "sdkExample"
        ];
        $testInstance->callStartSession($sessionStartData);

        $testInstance->setAuthToken64(null);
        $testInstance->setReqToken64(null);

        $finishStepData = [
            "applicant"=>    [
                "birthday"=>         '11.11.11',
                "email"=>            'test@test.test',
                "employmentStatus"=> 'intern',
                "firstName"=>       'bob',
                "gender"=>           'm',
                "lastName"=>         'dylan',
                "maritalStatus"=>    'single',
                "loan"=>            [
                    "amount"=>         15000,
                    "businessIncome"=> 15000,
                    "currency"=>       'PEN',
                    "personalIncome"=> 12000,
                    "term"=>           2.17
                ],
                "locale"=>           'en',
                "utcOffset"=>        '2',

                "addresses"=> [
                    "business"=> [
                        "city"=>       'asdf',
                        "country"=>    'USA',
                        "latitude"=>   12,
                        "longitude"=>  12,
                        "street"=>     'qwe',
                        "postalCode"=> 'qwe',
                        "region"=>     'qwer'
                    ],
                    "home"=>     [
                        "city"=>       'asdf',
                        "country"=>    'USA',
                        "latitude"=>   12,
                        "longitude"=>  12,
                        "street"=>     'qwe',
                        "postalCode"=> 'qwe',
                        "region"=>     'qwer'
                    ],
                    "work"=>     [
                        "city"=>       'asdf',
                        "country"=>    'USA',
                        "latitude"=>   12,
                        "longitude"=>  12,
                        "street"=>     'qwe',
                        "postalCode"=> 'qwe',
                        "region"=>     'qwer'
                    ],
                ],

                "connections"=> [
                    "facebook"=>   true,
                    "google"=>     true,
                    "linkedin"=>   true,
                    "microsoft"=>  true,
                    "twitter"=>    true,
                    "yahoo"=>      true
                ],

                "idNumbers"=> [
                    "analyticsId"=>        '123',
                    "bankAccountNumber"=>  '1234123413414',
                    "driversLicense"=>     '1234',
                    "externalKey"=>        '12341234',
                    "nationalId"=>         '1234123',
                    "passport"=>           '12341234134',
                    "phoneNumber"=>        '12341234',
                    "voterId"=>            '1234124'
                ]
            ],
            "device"=>       [
                "browser"=> null,
                "deviceId"=> null,
                "ipAddress"=> null,
                "os"=> [
                    "type"=> null,
                    "version"=> null,
                ],
                "referrer"=> null,

                "viewport"=> [
                    "height"=> null,
                    "width"=> null
                ]
            ],
            "metas"=>        new stdClass,
            "observations"=> new stdClass,
            "state"=>        new stdClass,
            "step"=>         'abGlobal',
        ];

        $response = $testInstance->callFinishStep($finishStepData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");
    }

    public function testCallGetApplicationOn403()
    {
        global $argv;
        if (isset($argv[2])){
            $file = fopen($argv[2], 'r');
            $file = fread($file, 10485760);
            $arguments = explode(PHP_EOL, $file);
        }
        else {
            $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
                'TestKeys/ApplicantJourney/identifier.txt',
                'TestKeys/ApplicantJourney/decryption.key',
                'TestKeys/ApplicantJourney/encryption.key'];
        }
        $testInstance = new AJAPIChildInt($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $sessionStartData = [
            "applicant"=> new stdClass(),
            "application"=> "sdkExample"
        ];
        $testInstance->callStartSession($sessionStartData);

        $testInstance->setAuthToken64(null);
        $testInstance->setReqToken64(null);

        $getApplicationData = [
            "device"=> [
                "browser"=> null,
                "deviceId"=> null,
                "ipAddress"=> null,
                "os"=> [
                    "type"=> null,
                    "version"=> null,
                ],
                "referrer"=> null,

                "viewport"=> [
                    "height"=> null,
                    "width"=> null
                ]
            ],
            "player"=> [
                "type"=>    "web-embedded",
                "version"=> "1.20"
            ]
        ];

        $response = $testInstance->callGetApplication($getApplicationData);
        $response = json_decode($response);
        $this->assertEquals($response->statusCode, 200, "Server must return status code 200.");
        $this->assertEquals($response->statusMessage, "OK", "Server must return status message OK.");
    }
}