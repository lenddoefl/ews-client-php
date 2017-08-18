<?php

namespace {
    require 'vendor/autoload.php';
}


namespace EWSPHPClient
{
    use GuzzleHttp\Client;

    abstract class EWSPMain
    {
        protected $identifier;
        protected $decryptionKey;
        protected $encryptionKey;

        protected $url;

        protected $authToken64;
        protected $reqToken64;

        //Methods setIdentifier, setDecryptionKey, setEncryptionKey lets user to set keys and identifier if they
        //weren't set in the controller.

        public function setIdentifier ($identifier)
        {
            $this->identifier = file_get_contents($identifier);
        }

        public function setDecryptionKey ($decryptionKey)
        {
            $this->decryptionKey = file_get_contents($decryptionKey);
        }

        public function setEncryptionKey ($encryptionKey)
        {
            $this->encryptionKey = file_get_contents($encryptionKey);
        }

        public function setURL ($url) {
            $this->url = trim($url, "/");
        }

        function __construct($url = null, $identifier = null, $decryptionKey = null, $encryptionKey = null)
        {
            $this->setIdentifier($identifier);
            $this->setDecryptionKey($decryptionKey);
            $this->setEncryptionKey($encryptionKey);
            $this->setURL($url);

        }

        /**
         * @param $url
         * @param $post
         * @return \Psr\Http\Message\StreamInterface
         * Use guzzle by default.
         */

        protected static function sendRequest ($url, $post)
        {

            $client = new Client();
            $response = $client->request('POST', $url, [
                'json' => $post
            ]);

            return $response->getBody();
        }


        public function callLogin ()
        {
            $url = $this->url . '/login.json';
            $post = [ "identifier"=> $this->identifier ];
            $response = $this::sendRequest($url, $post);

            return $response;
        }

        public function encoderDecoder ()
        {
            $login = \GuzzleHttp\json_decode($this->callLogin());

            $authToken64 = $login->data->authToken;
            $authToken = base64_decode($authToken64);

            $reqToken64 = $login->data->reqToken;

            $reqToken = base64_decode($reqToken64);
            $reqToken = openssl_decrypt($reqToken,'AES-128-CBC' ,$this->decryptionKey, OPENSSL_RAW_DATA, $authToken);
            $reqToken = openssl_encrypt($reqToken,'AES-128-CBC' ,$this->encryptionKey, OPENSSL_RAW_DATA, $authToken);
            $reqToken64 = base64_encode($reqToken);

            $this->authToken64 = $authToken64;
            $this->reqToken64 = $reqToken64;

            return [$authToken64, $reqToken64];
        }
    }
}

