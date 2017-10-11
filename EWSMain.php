<?php

namespace EFLGlobal\EWSClient
{
    use GuzzleHttp\Client;

    abstract class EWSMain
    {
        protected $identifier;
        protected $decryptionKey;
        protected $encryptionKey;

        protected $url;

        protected $authToken64;
        protected $reqToken64;

        abstract protected function extractTokensFromLoginResponse($login);

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

        public function getIdentifier ()
        {
            return $this->identifier;
        }

        public function getDecryptionKey ()
        {
            return $this->decryptionKey;
        }

        public function getEncryptionKey ()
        {
            return $this->encryptionKey;
        }

        public function getURL () {
            return $this->url;
        }

        function __construct($url , $identifier, $decryptionKey, $encryptionKey)
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
         * Uses guzzle.
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
            try{
                $response = static::sendRequest($url, $post);
                $this->encoderDecoder($response);

                return $response;
            }
            catch (\Exception $e) {
                return static::getError($e);
            }

        }

        protected function encoderDecoder ($login)
        {
            $login = \GuzzleHttp\json_decode($login);

            $tokens = $this->extractTokensFromLoginResponse($login);
            $authToken64 = $tokens[0];
            $reqToken64 = $tokens[1];

            $authToken = base64_decode($authToken64);
            $reqToken = base64_decode($reqToken64);

            $reqToken = openssl_decrypt($reqToken,'AES-128-CBC', $this->decryptionKey, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $authToken);
            $reqToken = openssl_encrypt($reqToken,'AES-128-CBC', $this->encryptionKey, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $authToken);
            $reqToken64 = base64_encode($reqToken);

            $this->authToken64 = $authToken64;
            $this->reqToken64 = $reqToken64;

            return [$authToken64, $reqToken64];
        }

        protected static function getError($error)
        {
            return $error;
        }
    }
}

