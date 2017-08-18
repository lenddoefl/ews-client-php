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

        protected $attachmentUids = [];

        public function callStartSession ($data)
        {
            $url = $this->url . '/startSession.json';
            $this->encoderDecoder();
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

        public function callCreateAttachment ($data)
        {
            //TODO Add attachment size check and attachment quantity check.

            $url = $this->url . 'createAttachment.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            $response = $this::sendRequest($url, $post);

            array_push($this->attachmentUids, \GuzzleHttp\json_encode($response)->data->attachmentUid);

            return $response;
        }


    }
}

