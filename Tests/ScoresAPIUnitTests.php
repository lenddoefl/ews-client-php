<?php
require_once __DIR__ . "/ChildClasses/ScoresAPIChild.php";

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use PHPUnit\Framework\TestCase;

class ScoresAPIUnitTests extends TestCase
{
    /**
     * In fact this method tests testing methods
     */

    public function testSendRequest()
    {

        $arguments = ['https://uat-external.eflglobal.com/api/v1/scores/',
            'TestKeys/Scores/identifier.txt',
            'TestKeys/Scores/decryption.key',
            'TestKeys/Scores/encryption.key'];
        $testInstance = new ScoresAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance::setMockData([
            new Response(200, [], "Call received"),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $response = $testInstance::sendRequest($arguments[0], "post");

        $this->assertEquals($response, "Call received", "Method sendRequest of child class works wrong. Tests cannot be performed as intended.");
    }

    public function testCallLogin()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v1/scores/',
            'TestKeys/Scores/identifier.txt',
            'TestKeys/Scores/decryption.key',
            'TestKeys/Scores/encryption.key'];
        $testInstance = new ScoresAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setDirectlyIdentifier("85677614E96A4CF4B1CE4A4E3F984CF7AFD63DE9A6438446EFD41D16F977D95D");
        $testInstance->setDirectlyEncryptionKey("96AC1E2955C4FFE3");
        $testInstance->setDirectlyDecryptionKey("5BB12CCFE9B52398");

        $data = [
            "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
            "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ==',
            "status" => 1,
            "statusMessage" => "Success"
        ];

        $data = json_encode($data);
        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);
        $response = $testInstance->callLogin();

        $this->assertEquals($data, $response, 'Method callLogin returns wrong result.');
        $this->assertAttributeEquals("nuYt+Y0sobcPXlYUgyQgkg==", 'authToken64', $testInstance, "Method encoderDecoder doesn't store authToken64.");
        $this->assertAttributeEquals("8zhoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==", 'reqToken64', $testInstance, "Method encoderDecoder doesn't store reqToken64.");
    }

    public function testCallDataQuery()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v1/scores/',
            'TestKeys/Scores/identifier.txt',
            'TestKeys/Scores/decryption.key',
            'TestKeys/Scores/encryption.key'];
        $testInstance = new ScoresAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $data = [
            "status" => 1,
            "statusMessage" => "Success",
            "subjects" => [
                [
                    "answerReliabilityFlag" => "Red",
                    "birthday" => "1878-04-24T00:00:00",
                    "eflId" => "4a7a796c-484b-42d0-b6a2-63e0c9f33b6f",
                    "fullname" => "Marcus Brody",
                    "identification" => [
                        [
                            "type" => "nationalId",
                            "value" => "E1CBCA6E"
                        ],
                        [
                            "type" => "bankAccountNumber",
                            "value" => "3996301848"
                        ]
                    ],
                    "scores" => [
                        [
                            "category" => null,
                            "createdDate" => "2016-01-25T16:17:22",
                            "decision" => "A",
                            "lastModifiedDate" => "2016-08-06T18:35:20",
                            "score" => 340,
                            "status" => 1,
                            "statusMessage" => "Success",
                            "version" => "Fox 20"
                        ]
                    ],
                    "status" => 1,
                    "statusMessage" => "Success"
                ],
                [
                    "answerReliabilityFlag" => "Green",
                    "birthday" => "1872-12-12T00:00:00",
                    "eflId" => "342b80d5-77a0-439d-bbaa-428ea582036b",
                    "fullname" => "Henry Walton Jones",
                    "identification" => [
                        [
                            "type" => "nationalId",
                            "value" => "1d8ecb02"
                        ],
                        [
                            "type" => "bankAccountNumber",
                            "value" => "42644d782"
                        ]
                    ],
                    "scores" => [
                        [
                            "category" => null,
                            "createdDate" => "2016-01-25T16:17:22",
                            "decision" => "D",
                            "lastModifiedDate" => "2016-08-06T18:35:20",
                            "score" => 218,
                            "status" => 1,
                            "statusMessage" => "Success",
                            "version" => "Fox 20"
                        ]
                    ],
                    "status" => 1,
                    "statusMessage" => "Success"
                ]
            ]
        ];

        $data = json_encode($data);
        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $response = $testInstance->callDateQuery(["dateQuery"=> "2017-03-14 00:00:00"]);

        $this->assertEquals($data, $response, "Method callDateQuery doesn't return right value.");
    }

    public function testCallSubject()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v1/scores/',
            'TestKeys/Scores/identifier.txt',
            'TestKeys/Scores/decryption.key',
            'TestKeys/Scores/encryption.key'];
        $testInstance = new ScoresAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $data = [
            "status" => 1,
            "statusMessage" => "Success",
            "subjects" => [
                [
                    "answerReliabilityFlag" => "Green",
                    "birthday" => "1909-03-23T00:00:00",
                    "eflId" => "5fc252b3-e1b6-479a-903b-b375dc91a61f",
                    "fullname" => "Marion Ravenwood",
                    "identification" => [
                        [
                            "type" => "passport",
                            "value" => "NN31001"
                        ]
                    ],
                    "scores" => [
                        [
                            "category" => null,
                            "createdDate" => "2016-01-25T16:17:22",
                            "decision" => null,
                            "lastModifiedDate" => "2016-08-06T18:35:20",
                            "score" => 223,
                            "status" => 1,
                            "statusMessage" => "Success",
                            "version" => "Fox 20"
                        ]
                    ],
                    "status" => 1,
                    "statusMessage" => "Success"
                ]
            ]
        ];


        $data = json_encode($data);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
            [
                "identification"=> [
                    [
                        "type"=> "nationalId",
                        "value"=> "DZ-015"
                    ]
                ]
            ]
        ];

        $response = $testInstance->callDateQuery($requestData);

        $this->assertEquals($data, $response, "Method callDateQuery doesn't return right value.");
    }
}
