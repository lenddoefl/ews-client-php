<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "AJAPIController.php";
use \EFLGlobal\EWSClient\AJAPIController;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;


class AJAPIChildInt extends AJAPIController
{

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

}