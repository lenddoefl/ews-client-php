<?php

require_once __DIR__ . "/../../ScoresAPIController.php";
use \EFLGlobal\EWSClient\ScoresAPIController;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;


class ScoresAPIChild extends ScoresAPIController
{
    protected static $mockData;

    protected $authToken64;
    protected $reqToken64;

    public function setDirectlyIdentifier ($identifier)
    {
        $this->identifier = $identifier;
    }

    public function setDirectlyDecryptionKey ($decryptionKey)
    {
        $this->decryptionKey = $decryptionKey;
    }

    public function setDirectlyEncryptionKey ($encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;
    }

    public function getAuthToken64 ()
    {
        return $this->authToken64;
    }

    public function getReqToken64 ()
    {
        return $this->reqToken64;
    }

    public static function setMockData($data)
    {
        self::$mockData = $data;
    }

    public static function sendRequest ($url, $post)
    {
    $mock = new MockHandler(self::$mockData);

    $handler = HandlerStack::create($mock);
    $client = new Client(['handler' => $handler]);
    $response = $client->request('POST', "hello", [
        'json' => $post
    ]);
    var_dump("!!!!!!!!!!!!!!!!!!");
    return $response->getBody();
    }
}