<?php
require_once __DIR__ . "/ChildClasses/ScoresApiChild.php";

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use PHPUnit\Framework\TestCase;

class ScoresAPIUnitTests extends TestCase
{
    /**
     * In fact tests testing methods
     */

    public function testSendRequest ()
    {

        $arguments = ['https://uat-external.eflglobal.com/api/v1/scores/',
            'TestKeys/Scores/identifier.txt',
            'TestKeys/Scores/decryption.key',
            'TestKeys/Scores/encryption.key'];
        $testInstance = new ScoresApiChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance::setMockData([
            new Response(200, [], "Call received"),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $response = $testInstance::sendRequest($arguments[0], "post");
        var_dump(get_class($testInstance));
        $this->assertEquals($response, "Call received");
    }

    public function testLogin ()
    {
        $arguments = ['https://uat-external.eflglobal.com/api/v1/scores/',
            'TestKeys/Scores/identifier.txt',
            'TestKeys/Scores/decryption.key',
            'TestKeys/Scores/encryption.key'];
        $testInstance = new ScoresApiChild($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

        $testInstance->setDirectlyIdentifier("85677614E96A4CF4B1CE4A4E3F984CF7AFD63DE9A6438446EFD41D16F977D95D");
        $testInstance->setDirectlyEncryptionKey("96AC1E2955C4FFE3");
        $testInstance->setDirectlyDecryptionKey("5BB12CCFE9B52398");

        $data = [
            "authToken"=> 'KXzqmJTk4pMZPJdcagUOaA==\n',
            "reqToken"=> 'gCe8L8UgKaMBjXd6s59liYN775dy0gJAVIIAP3fypLOFmh8xfWE6eqZLMuvbAWQhifYCuTdDAuWP\nFKV/rAr1kg==\n',
            "status"=> 1,
            "statusMessage"=> "Success"
        ];

        $data = json_encode($data);
///var_dump($data);
        $testInstance::setMockData([
            new Response(200, [], $data),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);
        var_dump(get_class($testInstance));

        $response = $testInstance->callLogin();
        //var_dump($response);
        //var_dump($testInstance->getIdentifier());
        //var_dump($testInstance->getAuthToken64());
        //var_dump($testInstance->getReqToken64());

    }
}

/*


print_r(ScoresAPIChild::sendRequest('hello', 'postData'));

ScoresAPIChild::setMockData([
    new Response(202, ['Content-Length' => 0]),
    new RequestException("Error Communicating with Server", new Request('GET', 'test'))
]);


print_r(ScoresAPIChild::sendRequest('hello', 'postData'));

ScoresAPIChild::setMockData([
    new Response(203, ['Something' => true]),
    new RequestException("Error Communicating with Server", new Request('GET', 'test'))
]);


print_r(ScoresAPIChild::sendRequest('hello', 'postData'));*/