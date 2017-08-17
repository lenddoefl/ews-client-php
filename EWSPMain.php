<?php

namespace EWSPHPClient
{
    abstract class EWSPMain
    {
        /**
         * @param $file
         * @return string
         * Method takes identifier file path and returns json, containing identifier.
         */
        protected static function getIdentifier ($file)
        {
            $identifier = file_get_contents($file);
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
         * @param $file
         * @return mixed
         * Method takes API login endpoint url and path to identifier file and returns API json answer.
         */

        public function callLogin ($url, $file)
        {
            $json = $this::getIdentifier($file);
            $response = $this::sendLoginRequest($url, $json);

            return $response;
        }
    }
}

