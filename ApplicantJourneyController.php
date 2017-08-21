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

        protected $sequence;
        protected $attachmentUids;

        public function getUid(){
            return $this->uid;
        }

        public function getPublicKey(){
            return $this->publicKey;
        }

        public function getAttachmentUids(){
            return $this->attachmentUids;
        }

        public function callStartSession ($data)
        {
            $this->sequence = 0;
            $this->attachmentUids = [];

            $url = $this->url . '/startSession.json';

            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            try {
                $response = self::sendRequest($url, $post);

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
            $response = self::sendRequest($url, $post);
            return $response;
        }

        public function callCreateAttachment ($data)
        {

            if (count($this->attachmentUids) < 10)
            {
                $url = $this->url . 'createAttachment.json';
                $post = [
                    "authToken"=>  $this->authToken64,
                    "reqToken"=>   $this->reqToken64,
                    "data"=>$data
                ];
                $response = self::sendRequest($url, $post);

                array_push($this->attachmentUids, \GuzzleHttp\json_encode($response)->data->attachmentUid);
                return $response;
            }
            else {
                return trigger_error("Too many attachments for this session.");
            }
        }

        public function callFinishStep ($data)
        {
            $url = $this->url . 'finishStep.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            $response = self::sendRequest($url, $post);
            return $response;
        }


        public function callGetApplication ($data)
        {
            $url = $this->url . 'getApplication.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            $response = self::sendRequest($url, $post);
            return $response;
        }

        public function callPrefetchApplications ($data)
        {
            $url = $this->url . 'prefetchApplications.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            $response = self::sendRequest($url, $post);
            return $response;
        }

        public function callResumeSession ($data)
        {
            $url = $this->url . 'resumeSession.json';
            if (!isset($data->uid)){
                $data->uid = $this->uid;
            }
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            try {
                $response = self::sendRequest($url, $post);

                $data = \GuzzleHttp\json_encode($response)->data;
                $this->publicKey = $data->publicKey;
                $this->uid = $data->uid;

                return $response;
            } catch (\Exception $e) {
                return var_dump($e);
            }
        }
    }
}

