<?php

namespace {
    include_once 'EWSPMain.php';
}

namespace EWSPHPClient

{
    class ApplicantJourneyController extends EWSPMain
    {

        protected $applicationHash;
        protected $publicKey;
        protected $uid;

        protected $sequence = 0;

        public function callStartSession ($data)
        {
            $url = $this->url . '/startSession.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            try {
                $response = $this::sendRequest($url, $post);

                $data = \GuzzleHttp\json_encode($response)->data;
                $this->applicationHash = $data->applicationHash;
                $this->publicKey = $data->publicKey;
                $this->uid = $data->uid;

                return $response;
            } catch (\Exception $e) {
                return var_dump($e);
            }
        }

        public function callFinishSession ($data)
        {
            $url = $this->url . 'inishSession.json';

            array_push($data, ['sequence'=> $this->sequence], ['uid'=> $this->uid]);

            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            $response = $this::sendRequest($url, $post);
            return $response;
        }


    }
}

