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

        function __construct($identifier = null, $decryptionKey = null, $encryptionKey = null)
        {
            $this->setIdentifier($identifier);
            $this->setDecryptionKey($decryptionKey);
            $this->setEncryptionKey($encryptionKey);
        }

        protected static function getKeys ()
        {

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

        /**
         * @param $url
         * @return mixed
         * Method takes API login endpoint url and path to identifier file and returns API json answer.
         */

        public function callLogin ($url)
        {
            $post = [ "identifier"=> $this->identifier ];
            $response = $this::sendRequest($url, $post);

            return $response;
        }
    }
}

