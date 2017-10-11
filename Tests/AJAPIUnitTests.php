<?php

if (strpos(__DIR__, DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'EFLGlobal'.DIRECTORY_SEPARATOR.'EWSClient'.DIRECTORY_SEPARATOR.'Tests'))
{
    include_once  __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'autoload.php';
}
require_once __DIR__ . DIRECTORY_SEPARATOR . "ChildClasses" . DIRECTORY_SEPARATOR . "AJAPIChild.php";

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use PHPUnit\Framework\TestCase;

class AJAPIUnitTests extends TestCase
{
    /**
     * In fact this method tests testing methods
     */
    public function testSendRequest()
    {

        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance::setMockData([
            new Response(200, [], "Call received"),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $response = $testInstance::sendRequest($arguments[0], "post");

        $this->assertEquals($response, "Call received", "Method sendRequest of child class works wrong. Tests cannot be performed as intended.");
    }

    public function testCallLogin()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setDirectlyIdentifier("85677614E96A4CF4B1CE4A4E3F984CF7AFD63DE9A6438446EFD41D16F977D95D");
        $testInstance->setDirectlyEncryptionKey("96AC1E2955C4FFE3");
        $testInstance->setDirectlyDecryptionKey("5BB12CCFE9B52398");

        $data = ['data'=>
            [
            "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
            "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
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

    public function testCallStartSession()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setDirectlyIdentifier("85677614E96A4CF4B1CE4A4E3F984CF7AFD63DE9A6438446EFD41D16F977D95D");
        $testInstance->setDirectlyEncryptionKey("96AC1E2955C4FFE3");
        $testInstance->setDirectlyDecryptionKey("5BB12CCFE9B52398");

        $data = ['data'=>
            [
                "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
                "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];
        $data1 = [
            "data"=> [
                "applicationHash"=> "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0",
                "publicKey"=> "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmCAmxt5yA1AA3QNXszXpePbJc/XZnKEVwbTIF8wEMn7OT2WFFd4+bJUMVOXaiKsT6jNrQgaAL3wGDougjXiDPdEc6PWEdRPB8m2XojnYgVE9blE5GqceL68SjuE3d7KDFxXJM8F6C4XUjJryGQWYXcQA8UCu5yWxJnGEFn4HuJkZIivCk0mSJyi7lT9HaxCR/HMZr/qZySjbCB0wfCoB+btdpEkEGjvsgWLZlhDwHoT4hXcOhzeYB2z/g4xGdGHgkuPv+QnG8aWDq3GxYGuu+nj2SZyL2CTKXG1kzSoys3lP3P/iJmd2yb/Rv8/7dq0aKTfr7aZfRql2L6aImhg+VwIDAQAB",
                "uid"=> "49c31aa34b054007888eb3feaf2516a2"
                ],
            "statusCode"=> 200,
            "statusMessage"=> "OK"
            ];

        $data = json_encode($data);
        $data1 = json_encode($data1);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new Response(200, [], $data1),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
        "applicant"=> ["name"=>"RandomName"],
        "application"=>   "sdkExample"
    ];
        $response = $testInstance->callStartSession($requestData);

        $this->assertEquals($data1, $response, 'Method callStartSession returns wrong result.');
        $this->assertAttributeEquals("nuYt+Y0sobcPXlYUgyQgkg==", 'authToken64', $testInstance, "Method encoderDecoder doesn't store authToken64.");
        $this->assertAttributeEquals("8zhoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==", 'reqToken64', $testInstance, "Method encoderDecoder doesn't store reqToken64.");
    }

    public function testCallStartSessionWithUidNotEmpty()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setDirectlyIdentifier("85677614E96A4CF4B1CE4A4E3F984CF7AFD63DE9A6438446EFD41D16F977D95D");
        $testInstance->setDirectlyEncryptionKey("96AC1E2955C4FFE3");
        $testInstance->setDirectlyDecryptionKey("5BB12CCFE9B52398");

        $testInstance->setUid('49c31aa34b054007888eb3feaf2516a2');

        $data = ['data'=>
            [
                "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
                "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];
        $data1 = [
            "data"=> [
                "applicationHash"=> "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0",
                "publicKey"=> "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmCAmxt5yA1AA3QNXszXpePbJc/XZnKEVwbTIF8wEMn7OT2WFFd4+bJUMVOXaiKsT6jNrQgaAL3wGDougjXiDPdEc6PWEdRPB8m2XojnYgVE9blE5GqceL68SjuE3d7KDFxXJM8F6C4XUjJryGQWYXcQA8UCu5yWxJnGEFn4HuJkZIivCk0mSJyi7lT9HaxCR/HMZr/qZySjbCB0wfCoB+btdpEkEGjvsgWLZlhDwHoT4hXcOhzeYB2z/g4xGdGHgkuPv+QnG8aWDq3GxYGuu+nj2SZyL2CTKXG1kzSoys3lP3P/iJmd2yb/Rv8/7dq0aKTfr7aZfRql2L6aImhg+VwIDAQAB",
                "uid"=> "49c31aa34b054007888eb3feaf2516a2"
            ],
            "statusCode"=> 200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);
        $data1 = json_encode($data1);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new Response(200, [], $data1),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
            "applicant"=> [],
            "application"=>   "sdkExample"
        ];
        $response = $testInstance->callStartSession($requestData);

        $position = strpos($response, $testInstance->getErrorUidIsSet());

        $this->assertNotFalse($position, 'Method callStartSession returns wrong result. Must throw error: ' . $testInstance->getErrorUidIsSet());
        $this->assertAttributeEmpty('authToken64', $testInstance, "AuthToken64 is not empty.");
        $this->assertAttributeEmpty('reqToken64', $testInstance, "ReqToken64 is not empty.");
    }

    public function testCallStartSessionResponse403()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setDirectlyIdentifier("85677614E96A4CF4B1CE4A4E3F984CF7AFD63DE9A6438446EFD41D16F977D95D");
        $testInstance->setDirectlyEncryptionKey("96AC1E2955C4FFE3");
        $testInstance->setDirectlyDecryptionKey("5BB12CCFE9B52398");

        $data = ['data'=>
            [
                "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
                "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];
        $data1 = [
            "data"=> [
                "applicationHash"=> "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0",
                "publicKey"=> "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmCAmxt5yA1AA3QNXszXpePbJc/XZnKEVwbTIF8wEMn7OT2WFFd4+bJUMVOXaiKsT6jNrQgaAL3wGDougjXiDPdEc6PWEdRPB8m2XojnYgVE9blE5GqceL68SjuE3d7KDFxXJM8F6C4XUjJryGQWYXcQA8UCu5yWxJnGEFn4HuJkZIivCk0mSJyi7lT9HaxCR/HMZr/qZySjbCB0wfCoB+btdpEkEGjvsgWLZlhDwHoT4hXcOhzeYB2z/g4xGdGHgkuPv+QnG8aWDq3GxYGuu+nj2SZyL2CTKXG1kzSoys3lP3P/iJmd2yb/Rv8/7dq0aKTfr7aZfRql2L6aImhg+VwIDAQAB",
                "uid"=> "49c31aa34b054007888eb3feaf2516a2"
            ],
            "statusCode"=> 200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);
        $data1 = json_encode($data1);

        $testInstance::setMockData([
            new Response(200, []),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'), new Response(403)),
            new Response(200, [], $data),
            new Response(200, [], $data1),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
            "applicant"=> [],
            "application"=>   "sdkExample"
        ];
        $response = $testInstance->callStartSession($requestData);


        $this->assertEquals($data1, $response, 'Method callStartSession returns wrong result.');
        $this->assertAttributeEquals("nuYt+Y0sobcPXlYUgyQgkg==", 'authToken64', $testInstance, "Method encoderDecoder doesn't store authToken64.");
        $this->assertAttributeEquals("8zhoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==", 'reqToken64', $testInstance, "Method encoderDecoder doesn't store reqToken64.");
    }

    public function testCallStartSessionAlreadyLogin()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setDirectlyIdentifier("85677614E96A4CF4B1CE4A4E3F984CF7AFD63DE9A6438446EFD41D16F977D95D");
        $testInstance->setDirectlyEncryptionKey("96AC1E2955C4FFE3");
        $testInstance->setDirectlyDecryptionKey("5BB12CCFE9B52398");
        $testInstance->setAuthToken64("nuYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("8zhoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data = [
            "data"=> [
                "applicationHash"=> "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0",
                "publicKey"=> "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmCAmxt5yA1AA3QNXszXpePbJc/XZnKEVwbTIF8wEMn7OT2WFFd4+bJUMVOXaiKsT6jNrQgaAL3wGDougjXiDPdEc6PWEdRPB8m2XojnYgVE9blE5GqceL68SjuE3d7KDFxXJM8F6C4XUjJryGQWYXcQA8UCu5yWxJnGEFn4HuJkZIivCk0mSJyi7lT9HaxCR/HMZr/qZySjbCB0wfCoB+btdpEkEGjvsgWLZlhDwHoT4hXcOhzeYB2z/g4xGdGHgkuPv+QnG8aWDq3GxYGuu+nj2SZyL2CTKXG1kzSoys3lP3P/iJmd2yb/Rv8/7dq0aKTfr7aZfRql2L6aImhg+VwIDAQAB",
                "uid"=> "49c31aa34b054007888eb3feaf2516a2"
            ],
            "statusCode"=> 200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
            "applicant"=> [],
            "application"=>   "sdkExample"
        ];
        $response = $testInstance->callStartSession($requestData);


        $this->assertEquals($data, $response, 'Method callStartSession returns wrong result.');
        $this->assertAttributeEquals("nuYt+Y0sobcPXlYUgyQgkg==", 'authToken64', $testInstance, "Method encoderDecoder doesn't store authToken64.");
        $this->assertAttributeEquals("8zhoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==", 'reqToken64', $testInstance, "Method encoderDecoder doesn't store reqToken64.");
    }

    public function testCallResumeSession()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setDirectlyIdentifier("85677614E96A4CF4B1CE4A4E3F984CF7AFD63DE9A6438446EFD41D16F977D95D");
        $testInstance->setDirectlyEncryptionKey("96AC1E2955C4FFE3");
        $testInstance->setDirectlyDecryptionKey("5BB12CCFE9B52398");

        $testInstance->setUid('asdfasdfdasdfasdf');

        $data = ['data'=>
            [
                "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
                "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];
        $data1 = [
            "data"=> [
                "applicationHash"=> "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0",
                "publicKey"=> "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmCAmxt5yA1AA3QNXszXpePbJc/XZnKEVwbTIF8wEMn7OT2WFFd4+bJUMVOXaiKsT6jNrQgaAL3wGDougjXiDPdEc6PWEdRPB8m2XojnYgVE9blE5GqceL68SjuE3d7KDFxXJM8F6C4XUjJryGQWYXcQA8UCu5yWxJnGEFn4HuJkZIivCk0mSJyi7lT9HaxCR/HMZr/qZySjbCB0wfCoB+btdpEkEGjvsgWLZlhDwHoT4hXcOhzeYB2z/g4xGdGHgkuPv+QnG8aWDq3GxYGuu+nj2SZyL2CTKXG1kzSoys3lP3P/iJmd2yb/Rv8/7dq0aKTfr7aZfRql2L6aImhg+VwIDAQAB",
                "uid"=> "49c31aa34b054007888eb3feaf2516a2"
            ],
            "statusCode"=> 200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);
        $data1 = json_encode($data1);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new Response(200, [], $data1),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
            "applicant"=> [],
        ];
        $testInstance->callLogin();
        $response = $testInstance->callResumeSession($requestData);

        $this->assertEquals($data1, $response, 'Method callResumeSession returns wrong result.');
        $this->assertAttributeEquals("nuYt+Y0sobcPXlYUgyQgkg==", 'authToken64', $testInstance, "Method encoderDecoder doesn't store authToken64.");
        $this->assertAttributeEquals("8zhoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==", 'reqToken64', $testInstance, "Method encoderDecoder doesn't store reqToken64.");
    }

    public function testCallResumeSessionWithUidEmpty()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setDirectlyIdentifier("85677614E96A4CF4B1CE4A4E3F984CF7AFD63DE9A6438446EFD41D16F977D95D");
        $testInstance->setDirectlyEncryptionKey("96AC1E2955C4FFE3");
        $testInstance->setDirectlyDecryptionKey("5BB12CCFE9B52398");

        $data = ['data'=>
            [
                "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
                "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];
        $data1 = [
            "data"=> [
                "applicationHash"=> "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0",
                "publicKey"=> "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmCAmxt5yA1AA3QNXszXpePbJc/XZnKEVwbTIF8wEMn7OT2WFFd4+bJUMVOXaiKsT6jNrQgaAL3wGDougjXiDPdEc6PWEdRPB8m2XojnYgVE9blE5GqceL68SjuE3d7KDFxXJM8F6C4XUjJryGQWYXcQA8UCu5yWxJnGEFn4HuJkZIivCk0mSJyi7lT9HaxCR/HMZr/qZySjbCB0wfCoB+btdpEkEGjvsgWLZlhDwHoT4hXcOhzeYB2z/g4xGdGHgkuPv+QnG8aWDq3GxYGuu+nj2SZyL2CTKXG1kzSoys3lP3P/iJmd2yb/Rv8/7dq0aKTfr7aZfRql2L6aImhg+VwIDAQAB",
                "uid"=> "49c31aa34b054007888eb3feaf2516a2"
            ],
            "statusCode"=> 200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);
        $data1 = json_encode($data1);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new Response(200, [], $data1),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
            "applicant"=> [],
            "application"=>   "sdkExample"
        ];

        $testInstance->callLogin();
        $response = $testInstance->callResumeSession($requestData);

        $position = strpos($response, $testInstance->getErrorUidNotSet());

        $this->assertNotFalse($position, 'Method callResumeSession returns wrong result. Must throw error: ' . $testInstance->getErrorUidNotSet());
    }

    public function testCallPrefetchApplication()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setDirectlyIdentifier("85677614E96A4CF4B1CE4A4E3F984CF7AFD63DE9A6438446EFD41D16F977D95D");
        $testInstance->setDirectlyEncryptionKey("96AC1E2955C4FFE3");
        $testInstance->setDirectlyDecryptionKey("5BB12CCFE9B52398");

        $data = ['data'=>
            [
                "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
                "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];
        $data1 = [
            "data"=> [
                "data"=> [
                    "applications"=> [
                        'String'=> [
                            "applicationHash"=>      'String',
                            "playerConfiguration"=>  'PlayerObject',
                            "useCached"=>            'Boolean'
                        ]
                    ]
                ]
            ]
        ];


        $data = json_encode($data);
        $data1 = json_encode($data1);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new Response(200, [], $data1),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = ["applications" => ["sdkExample"=>   "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0"]];

        $response = $testInstance->callPrefetchApplications($requestData);

        $this->assertEquals($data1, $response, 'Method callPrefetchApplications returns wrong result.');
        $this->assertAttributeEquals("nuYt+Y0sobcPXlYUgyQgkg==", 'authToken64', $testInstance, "Method encoderDecoder doesn't store authToken64.");
        $this->assertAttributeEquals("8zhoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==", 'reqToken64', $testInstance, "Method encoderDecoder doesn't store reqToken64.");
    }

    public function testCallPrefetchApplicationsResponse403()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setDirectlyIdentifier("85677614E96A4CF4B1CE4A4E3F984CF7AFD63DE9A6438446EFD41D16F977D95D");
        $testInstance->setDirectlyEncryptionKey("96AC1E2955C4FFE3");
        $testInstance->setDirectlyDecryptionKey("5BB12CCFE9B52398");

        $testInstance->setUid("qwertrwe");

        $data = ['data'=>
            [
                "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
                "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];
        $data1 = [
            "data"=> [
                "data"=> [
                    "applications"=> [
                        'String'=> [
                            "applicationHash"=>      'String',
                            "playerConfiguration"=>  'PlayerObject',
                            "useCached"=>            'Boolean'
                        ]
                    ]
                ]
            ]
        ];
        $data = json_encode($data);
        $data1 = json_encode($data1);

        $testInstance::setMockData([
            new Response(200, []),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'), new Response(403)),
            new Response(200, [], $data),
            new Response(200, [], $data1),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = ["applications" => ["sdkExample"=>   "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0"]];

        $response = $testInstance->callPrefetchApplications($requestData);


        $this->assertEquals($data1, $response, 'Method callPrefetchApplications returns wrong result.');
        $this->assertAttributeEquals("nuYt+Y0sobcPXlYUgyQgkg==", 'authToken64', $testInstance, "Method encoderDecoder doesn't store authToken64.");
        $this->assertAttributeEquals("8zhoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==", 'reqToken64', $testInstance, "Method encoderDecoder doesn't store reqToken64.");
    }

    public function testCallPrefetchApplicationsAlreadyLogin()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setDirectlyIdentifier("85677614E96A4CF4B1CE4A4E3F984CF7AFD63DE9A6438446EFD41D16F977D95D");
        $testInstance->setDirectlyEncryptionKey("96AC1E2955C4FFE3");
        $testInstance->setDirectlyDecryptionKey("5BB12CCFE9B52398");

        $testInstance->setUid("qwertrwe");
        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data = ['data'=>
            [
                "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
                "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];
        $data1 = [
            "data"=> [
                "data"=> [
                    "applications"=> [
                        'String'=> [
                            "applicationHash"=>      'String',
                            "playerConfiguration"=>  'PlayerObject',
                            "useCached"=>            'Boolean'
                        ]
                    ]
                ]
            ]
        ];

        $data = json_encode($data);
        $data1 = json_encode($data1);

        $testInstance::setMockData([
            new RequestException("Error Communicating with Server", new Request('GET', 'test'), new Response(403)),
            new Response(200, [], $data),
            new Response(200, [], $data1),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = ["applications" => ["sdkExample"=>   "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0"]];

        $response = $testInstance->callPrefetchApplications($requestData);

        $this->assertEquals($data1, $response, 'Method callPrefetchApplications returns wrong result.');
        $this->assertAttributeEquals("nuYt+Y0sobcPXlYUgyQgkg==", 'authToken64', $testInstance, "Method encoderDecoder doesn't store authToken64.");
        $this->assertAttributeEquals("8zhoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==", 'reqToken64', $testInstance, "Method encoderDecoder doesn't store reqToken64.");
    }

    public function testCallCreateAttachment()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setUid("qwertrwe");
        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data = [
            "data" => ["attachmentUid"=> 'ae19b02eb8b749dfabbe666ae0bcac0b'],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
            ];

        $data = json_encode($data);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
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

        $response = $testInstance->callCreateAttachment($requestData);

        $this->assertEquals($data, $response, 'Method callCreateAttachment returns wrong result.');
        $this->assertAttributeEquals(["ae19b02eb8b749dfabbe666ae0bcac0b"], 'attachmentUids', $testInstance, "Method callCreateAttachment doesn't store attachment uids.");
    }

    public function testCallCreateAttachmentWithoutUid()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data = [
            "data" => ["attachmentUid"=> 'ae19b02eb8b749dfabbe666ae0bcac0b'],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
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

        $response = $testInstance->callCreateAttachment($requestData);

        $position = strpos($response, $testInstance->getErrorUidNotSet());

        $this->assertNotFalse($position, 'Method callCreateAttachment returns wrong result. Must throw error: ' . $testInstance->getErrorUidNotSet());
    }

    public function testCallFinishSession ()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setUid("qwertrwe");
        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data = [
            "data" => [],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $response = $testInstance->callFinishSession();

        $this->assertEquals($data, $response, 'Method callFinishSession returns wrong result.');
    }

    public function testCallFinishSessionWithoutUid ()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data = [
            "data" => [],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $response = $testInstance->callFinishSession();

        $position = strpos($response, $testInstance->getErrorUidNotSet());

        $this->assertNotFalse($position, 'Method callFinishSession returns wrong result. Must throw error: ' . $testInstance->getErrorUidNotSet());
    }

    public function testCallFinishStep ()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setUid("qwertrwe");
        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data = [
            "data" => [],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
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

        $response = $testInstance->callFinishStep($requestData);

        $this->assertEquals($data, $response, 'Method callFinishStep returns wrong result.');
        $this->assertAttributeEquals(1, 'sequence', $testInstance, "Method callFinishStep doesn't add one to sequence.");
    }

    public function testCallFinishStepWithoutUid ()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data = [
            "data" => [],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
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
        $response = $testInstance->callFinishStep($requestData);

        $position = strpos($response, $testInstance->getErrorUidNotSet());
        $this->assertNotFalse($position, 'Method callFinishStep returns wrong result. Must throw error: ' . $testInstance->getErrorUidNotSet());
    }

    public function testCallGetApplication ()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setUid("qwertrwe");
        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data =
            [
                "data" => [
                    "applicant" => new stdClass(),
                    "applicationHash" => "aasdfasde43fq4q3f4rqr423r",
                    "playerConfiguration" => new stdClass(),
                    "useCached" => true,

                    "state" => [
                        "completedSteps" => ["String"]
                    ],
                ],

                "statusCode" => 200,
                "statusMessage" => "OK"
            ];

        $data = json_encode($data);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
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

        $response = $testInstance->callGetApplication($requestData);

        $this->assertEquals($data, $response, 'Method callGetApplication returns wrong result.');
    }

    public function testCallGetApplicationWithoutUid ()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data =
            [
                "data" => [
                    "applicant" => new stdClass(),
                    "applicationHash" => "aasdfasde43fq4q3f4rqr423r",
                    "playerConfiguration" => new stdClass(),
                    "useCached" => true,

                    "state" => [
                        "completedSteps" => ["String"]
                    ],
                ],

                "statusCode" => 200,
                "statusMessage" => "OK"
            ];

        $data = json_encode($data);

        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
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

        $response = $testInstance->callGetApplication($requestData);

        $position = strpos($response, $testInstance->getErrorUidNotSet());
        $this->assertNotFalse($position, 'Method callGetApplication returns wrong result. Must throw error: ' . $testInstance->getErrorUidNotSet());
    }

    public function testCallCreateAttachmentOn403()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setUid("qwertrwe");
        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data = ['data'=>
            [
                "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
                "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];
        $data1 = [
            "data"=> [
                "applicationHash"=> "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0",
                "publicKey"=> "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmCAmxt5yA1AA3QNXszXpePbJc/XZnKEVwbTIF8wEMn7OT2WFFd4+bJUMVOXaiKsT6jNrQgaAL3wGDougjXiDPdEc6PWEdRPB8m2XojnYgVE9blE5GqceL68SjuE3d7KDFxXJM8F6C4XUjJryGQWYXcQA8UCu5yWxJnGEFn4HuJkZIivCk0mSJyi7lT9HaxCR/HMZr/qZySjbCB0wfCoB+btdpEkEGjvsgWLZlhDwHoT4hXcOhzeYB2z/g4xGdGHgkuPv+QnG8aWDq3GxYGuu+nj2SZyL2CTKXG1kzSoys3lP3P/iJmd2yb/Rv8/7dq0aKTfr7aZfRql2L6aImhg+VwIDAQAB",
                "uid"=> "49c31aa34b054007888eb3feaf2516a2"
            ],
            "statusCode"=> 200,
            "statusMessage"=> "OK"
        ];
        $data2 = [
            "data" => ["attachmentUid"=> 'ae19b02eb8b749dfabbe666ae0bcac0b'],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);
        $data1 = json_encode($data1);
        $data2 = json_encode($data2);

        $testInstance::setMockData([
            new Response(403, []),
            new Response(200, [], $data),
            new Response(200, [], $data1),
            new Response(200, [], $data2),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
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

        $response = $testInstance->callCreateAttachment($requestData);

        $this->assertEquals($data2, $response, 'Method callCreateAttachment returns wrong result.');
        $this->assertAttributeEquals(["ae19b02eb8b749dfabbe666ae0bcac0b"], 'attachmentUids', $testInstance, "Method callCreateAttachment doesn't store attachment uids.");
    }

    public function testCallFinishSessionOn403 ()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setUid("qwertrwe");
        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data = ['data'=>
            [
                "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
                "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];
        $data1 = [
            "data"=> [
                "applicationHash"=> "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0",
                "publicKey"=> "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmCAmxt5yA1AA3QNXszXpePbJc/XZnKEVwbTIF8wEMn7OT2WFFd4+bJUMVOXaiKsT6jNrQgaAL3wGDougjXiDPdEc6PWEdRPB8m2XojnYgVE9blE5GqceL68SjuE3d7KDFxXJM8F6C4XUjJryGQWYXcQA8UCu5yWxJnGEFn4HuJkZIivCk0mSJyi7lT9HaxCR/HMZr/qZySjbCB0wfCoB+btdpEkEGjvsgWLZlhDwHoT4hXcOhzeYB2z/g4xGdGHgkuPv+QnG8aWDq3GxYGuu+nj2SZyL2CTKXG1kzSoys3lP3P/iJmd2yb/Rv8/7dq0aKTfr7aZfRql2L6aImhg+VwIDAQAB",
                "uid"=> "49c31aa34b054007888eb3feaf2516a2"
            ],
            "statusCode"=> 200,
            "statusMessage"=> "OK"
        ];
        $data2 = [
            "data" => [],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);
        $data1 = json_encode($data1);
        $data2 = json_encode($data2);

        $testInstance::setMockData([
            new Response(403, []),
            new Response(200, [], $data),
            new Response(200, [], $data1),
            new Response(200, [], $data2),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $response = $testInstance->callFinishSession();

        $this->assertEquals($data2, $response, 'Method callFinishSession returns wrong result.');
    }

    public function testCallFinishStepOn403 ()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setUid("qwertrwe");
        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data = ['data'=>
            [
                "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
                "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];
        $data1 = [
            "data"=> [
                "applicationHash"=> "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0",
                "publicKey"=> "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmCAmxt5yA1AA3QNXszXpePbJc/XZnKEVwbTIF8wEMn7OT2WFFd4+bJUMVOXaiKsT6jNrQgaAL3wGDougjXiDPdEc6PWEdRPB8m2XojnYgVE9blE5GqceL68SjuE3d7KDFxXJM8F6C4XUjJryGQWYXcQA8UCu5yWxJnGEFn4HuJkZIivCk0mSJyi7lT9HaxCR/HMZr/qZySjbCB0wfCoB+btdpEkEGjvsgWLZlhDwHoT4hXcOhzeYB2z/g4xGdGHgkuPv+QnG8aWDq3GxYGuu+nj2SZyL2CTKXG1kzSoys3lP3P/iJmd2yb/Rv8/7dq0aKTfr7aZfRql2L6aImhg+VwIDAQAB",
                "uid"=> "49c31aa34b054007888eb3feaf2516a2"
            ],
            "statusCode"=> 200,
            "statusMessage"=> "OK"
        ];
        $data2 = [
            "data" => [],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];

        $data = json_encode($data);
        $data1 = json_encode($data1);
        $data2 = json_encode($data2);

        $testInstance::setMockData([
            new Response(403, []),
            new Response(200, [], $data),
            new Response(200, [], $data1),
            new Response(200, [], $data2),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
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

        $response = $testInstance->callFinishStep($requestData);

        $this->assertEquals($data2, $response, 'Method callFinishStep returns wrong result.');
        $this->assertAttributeEquals(1, 'sequence', $testInstance, "Method callFinishStep doesn't add one to sequence.");
    }

    public function testCallGetApplicationOn403 ()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt',
            __DIR__. '/MockData/MockData.txt'];
        $testInstance = new AJAPIChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setUid("qwertrwe");
        $testInstance->setAuthToken64("mrYt+Y0sobcPXlYUgyQgkg==");
        $testInstance->setReqToken64("7ihoCt9TzaxSCPlNLQ5rMDlgpNTWbPvDcKj+6qrsLFUEs/kNL/dlVAkAm/BjW1wy/MZAH3w+F0HYqt0xABXIkg==");

        $data = ['data'=>
            [
                "authToken" => 'nuYt+Y0sobcPXlYUgyQgkg==',
                "reqToken" => 'Pr1vlujWJPAvnTHP3cUugAkinmdOyjABkkEYr/1QwTD4nXOPTSUgER5c1xhLkyRuLvJmLk49j6t1GluostIOFQ=='
            ],
            "statusCode"=>    200,
            "statusMessage"=> "OK"
        ];
        $data1 = [
            "data"=> [
                "applicationHash"=> "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0",
                "publicKey"=> "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmCAmxt5yA1AA3QNXszXpePbJc/XZnKEVwbTIF8wEMn7OT2WFFd4+bJUMVOXaiKsT6jNrQgaAL3wGDougjXiDPdEc6PWEdRPB8m2XojnYgVE9blE5GqceL68SjuE3d7KDFxXJM8F6C4XUjJryGQWYXcQA8UCu5yWxJnGEFn4HuJkZIivCk0mSJyi7lT9HaxCR/HMZr/qZySjbCB0wfCoB+btdpEkEGjvsgWLZlhDwHoT4hXcOhzeYB2z/g4xGdGHgkuPv+QnG8aWDq3GxYGuu+nj2SZyL2CTKXG1kzSoys3lP3P/iJmd2yb/Rv8/7dq0aKTfr7aZfRql2L6aImhg+VwIDAQAB",
                "uid"=> "49c31aa34b054007888eb3feaf2516a2"
            ],
            "statusCode"=> 200,
            "statusMessage"=> "OK"
        ];
        $data2 =
            [
                "data" => [
                    "applicant" => new stdClass(),
                    "applicationHash" => "aasdfasde43fq4q3f4rqr423r",
                    "playerConfiguration" => new stdClass(),
                    "useCached" => true,

                    "state" => [
                        "completedSteps" => ["String"]
                    ],
                ],

                "statusCode" => 200,
                "statusMessage" => "OK"
            ];

        $data = json_encode($data);
        $data1 = json_encode($data1);
        $data2 = json_encode($data2);

        $testInstance::setMockData([
            new Response(403, []),
            new Response(200, [], $data),
            new Response(200, [], $data1),
            new Response(200, [], $data2),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $requestData = [
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

        $response = $testInstance->callGetApplication($requestData);

        $this->assertEquals($data2, $response, 'Method callGetApplication returns wrong result.');
    }
}

