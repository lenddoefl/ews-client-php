<?php

namespace EWSPHPClient
{
    abstract class EWSPMain
    {
        protected $identifier;
        protected $decryptionKey;
        protected $encryptionKey;

        //Methods setIdentifier, setDecryptionKey, setEncryptionKey lets user to set keys and identifier if they
        //weren't set in the controller.

        public function setIdentifier ($identifier)
        {
            $this->identifier = $identifier;
        }

        public function setDecryptionKey ($decryptionKey)
        {
            $this->decryptionKey = $decryptionKey;
        }

        public function setEncryptionKey ($encryptionKey)
        {
            $this->encryptionKey = $encryptionKey;
        }

        function __construct($identifier = null, $decryptionKey = null, $encryptionKey = null)
        {
            $this->setIdentifier($identifier);
            $this->setDecryptionKey($decryptionKey);
            $this->setEncryptionKey($encryptionKey);
        }

        protected static function getJsonIdentifier ($identifier)
        {
            $identifier = file_get_contents($identifier);
            $testPost = [ "identifier"=> $identifier ];
            $json = json_encode($testPost);
            return $json;
        }

        protected static function getKeys ()
        {

        }

        protected static function sendRequest ()
        {

        }

        /**
         * @param $url
         * @param $json
         * @return mixed
         * Curl-based implementation is placeholder for test purposes.
         */
        protected static function sendLoginRequest ($url, $json)
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_URL => $url,
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            return $response;
        }

        /**
         * @param $url
         * @return mixed
         * Method takes API login endpoint url and path to identifier file and returns API json answer.
         */

        public function callLogin ($url)
        {
            $json = $this::getJsonIdentifier($this->identifier);
            $response = $this::sendLoginRequest($url, $json);

            return $response;
        }
    }
}

