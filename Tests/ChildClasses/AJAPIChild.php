<?php

use \EFLGlobal\EWSClient\AJAPIController;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;


class AJAPIChild extends AJAPIController
{
    public static $mockData;

    public function getErrorUidIsSet ()
    {
        return $this->errorUidIsSet;
    }

    public function getErrorUidNotSet ()
    {
        return $this->errorUidNotSet;
    }


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

    public function setAuthToken64($authToken64)
    {
        $this->authToken64 = $authToken64;
    }

    public function setReqToken64 ($reqToken64)
    {
        $this->reqToken64 = $reqToken64;
    }

    public static function setMockData($data)
    {
        self::$mockData = $data;
    }

    public static function sendRequest ($url, $post)
    {
        $mock = new MockHandler(self::$mockData);
        array_shift(self::$mockData);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $response = $client->request('POST', "hello", [
            'json' => $post
        ]);

        return $response->getBody();
    }
}
