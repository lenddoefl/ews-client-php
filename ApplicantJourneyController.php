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

                $data = \GuzzleHttp\json_decode($response)->data;
                $this->applicationHash = $data->applicationHash;
                $this->publicKey = $data->publicKey;
                $this->uid = $data->uid;

                return $response;
            } catch (\Exception $e) {
                return self::handleError($e);
            }
        }

        public function callFinishSession ($data)
        {
            $url = $this->url . '/finishSession.json';

            if (!isset($data->uid)){
                $data->uid = $this->uid;
            }
            if (!isset($data->sequence)){
                $data->uid = $this->sequence;
            }

            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];

            try {
                $response = self::sendRequest($url, $post);
                return $response;
            } catch (\Exception $e) {
                return self::handleError($e);
            }
        }

        public function callCreateAttachment ($data)
        {

            if (count($this->attachmentUids) < 10)
            {
                $url = $this->url . '/createAttachment.json';
                $post = [
                    "authToken"=>  $this->authToken64,
                    "reqToken"=>   $this->reqToken64,
                    "data"=>$data
                ];

                try {
                    $response = self::sendRequest($url, $post);

                    array_push($this->attachmentUids, \GuzzleHttp\json_decode($response)->data->attachmentUid);
                    return $response;
                } catch (\Exception $e) {
                    return self::handleError($e);
                }
            }
            else {
                return trigger_error("Too many attachments for this session.");
            }
        }

        public function callFinishStep ($data)
        {
            $url = $this->url . '/finishStep.json';

            if (!isset($data->uid)){
                $data->uid = $this->uid;
            }
            if (!isset($data->sequence)){
                $data->uid = $this->sequence;
            }

            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            try {
                $response = self::sendRequest($url, $post);
                $this->sequence += 1;

                return $response;
            } catch (\Exception $e) {
                return self::handleError($e);
            }
        }


        public function callGetApplication ($data)
        {
            $url = $this->url . '/getApplication.json';

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
                return $response;
            } catch (\Exception $e) {
                return self::handleError($e);
            }

        }

        public function callPrefetchApplications ($data)
        {
            $url = $this->url . '/prefetchApplications.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            try {
                $response = self::sendRequest($url, $post);
                return $response;
            } catch (\Exception $e) {
                return self::handleError($e);
            }
        }

        public function callResumeSession ($data)
        {
            $url = $this->url . '/resumeSession.json';
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

                $data = \GuzzleHttp\json_decode($response)->data;
                $this->publicKey = $data->publicKey;
                $this->uid = $data->uid;

                return $response;
            } catch (\Exception $e) {
                return self::handleError($e);
            }
        }
    }
}

