<?php

namespace {
    include_once 'EWSMain.php';
}

namespace EFLGlobal\EWSClient

{

    class AJAPIController extends EWSMain
    {

        protected $applicationHash;
        protected $publicKey;
        protected $uid;

        protected $sequence;
        protected $attachmentUids;

        protected $errorUidNotSet = "You don't have active uid. Probably you haven't started session.";
        protected $errorUidIsSet = "You have active uid. Probably you have already started session.";

        protected $applicant = [];

        public function getUid()
        {
            return $this->uid;
        }

        public function getPublicKey()
        {
            return $this->publicKey;
        }

        public function getAttachmentUids()
        {
            return $this->attachmentUids;
        }

        public function getApplicationHash()
        {
            return $this->applicationHash;
        }

        public function setUid($uid)
        {
            $this->uid = $uid;
        }

        public function getApplicant()
        {
            return $this->applicant;
        }

        public function setApplicant($applicant)
        {
            $this->applicant = $applicant;
        }

        function __construct($url , $identifier, $decryptionKey, $encryptionKey)
        {
            parent::__construct($url , $identifier, $decryptionKey, $encryptionKey);
            $this->attachmentUids = [];
        }

        public function callStartSession ($data, $repeat=true)
        {
            try {
                if (isset($this->uid)) {
                    throw new \Exception($this->errorUidIsSet);
                }
            }
            catch (\Exception $e){
                return static::getError($e);
            }

            if ((!isset($this->authToken64)) and ($repeat==true)){
                $this->callLogin();
            }

            $this->sequence = 0;
            $this->attachmentUids = [];

            $url = $this->url . '/startSession.json';

            if (isset($this->applicationHash)){
                $data['applicationHash'] = $this->applicationHash;
            }
            $this->applicant = $data->applicant;

            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            try {
                $response = static::sendRequest($url, $post);

                $data = \GuzzleHttp\json_decode($response)->data;
                $this->applicationHash = $data->applicationHash;
                $this->publicKey = $data->publicKey;
                $this->uid = $data->uid;

                return $response;
            } catch (\Exception $e) {
                if (($e->getCode() == 403) and ($repeat===true)) {
                    $this->callLogin();
                    return $this->callStartSession($data, false);
                }
                else {
                    return static::getError($e);
                }
            }
        }

        public function callFinishSession ($data = [], $repeat=true)
        {
            try {
                if (!isset($this->uid)) {
                    throw new \Exception($this->errorUidNotSet);
                }
            }
            catch (\Exception $e){
                return static::getError($e);
            }

            $url = $this->url . '/finishSession.json';

            $data['uid'] = $this->uid;

            if (!isset($data->sequence)){
                $data['sequence'] = $this->sequence;
            }

            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];

            try {
                $response = static::sendRequest($url, $post);
                return $response;
            } catch (\Exception $e) {
                if (($e->getCode() == 403) and ($repeat===true)) {
                    $this->callLogin();
                    $this->callResumeSession();
                    return $this->callFinishSession($data, false);
                }
                else {
                    return static::getError($e);
                }
            }
        }

        public function callCreateAttachment ($data, $repeat=true)
        {
            try {
                if (!isset($this->uid)) {
                    throw new \Exception($this->errorUidNotSet);
                }
            }
            catch (\Exception $e){
                return static::getError($e);
            }

            $data['uid'] = $this->uid;

            $url = $this->url . '/createAttachment.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];

            try {
                $response = static::sendRequest($url, $post);

                array_push($this->attachmentUids, \GuzzleHttp\json_decode($response)->data->attachmentUid);
                return $response;
            } catch (\Exception $e) {
                if (($e->getCode() == 403) and ($repeat===true)) {
                    $this->callLogin();
                    $this->callResumeSession();
                    return $this->callCreateAttachment($data, false);
                }
                else {
                    return static::getError($e);
                }
            }

        }

        public function callFinishStep ($data, $repeat=true)
        {
            try {
                if (!isset($this->uid)) {
                    throw new \Exception($this->errorUidNotSet);
                }
            }
            catch (\Exception $e){
                return static::getError($e);
            }

            $url = $this->url . '/finishStep.json';

            $data['uid'] = $this->uid;

            if (!isset($data->sequence)){
                $data['sequence'] = $this->sequence;
            }

            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            try {
                $response = static::sendRequest($url, $post);
                $this->sequence++;

                return $response;
            } catch (\Exception $e) {
                if (($e->getCode() == 403) and ($repeat===true)) {
                    $this->callLogin();
                    $this->callResumeSession();
                    return $this->callFinishStep($data, false);
                }
                else {
                    return static::getError($e);
                }
            }
        }

        public function callGetApplication ($data, $repeat=true)
        {
            try {
                if (!isset($this->uid)) {
                    throw new \Exception($this->errorUidNotSet);
                }
            }
            catch (\Exception $e){
                return static::getError($e);
            }

            $url = $this->url . '/getApplication.json';

            $data['uid'] = $this->uid;

            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];

            try {
                $response = static::sendRequest($url, $post);

                return $response;
            } catch (\Exception $e) {
                if (($e->getCode() == 403) and ($repeat===true)) {
                    $this->callLogin();
                    $this->callResumeSession();
                    return $this->callGetApplication($data, false);
                }
                else {
                    return static::getError($e);
                }
            }
        }

        public function callPrefetchApplications ($data, $repeat=true)
        {
            if ((!isset($this->authToken64)) and ($repeat==true)){
                $this->callLogin();
            }

            $url = $this->url . '/prefetchApplications.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            try {
                $response = static::sendRequest($url, $post);
                return $response;
            } catch (\Exception $e) {
                if (($e->getCode() == 403) and ($repeat===true)) {
                    $this->callLogin();
                    return $this->callPrefetchApplications($data, false);
                }
                else {
                    return static::getError($e);
                }
            }
        }

        public function callResumeSession ($data=[])
        {
            try {
                if (!isset($this->uid)) {
                    throw new \Exception($this->errorUidNotSet);
                }
            }
            catch (\Exception $e){
                return static::getError($e);
            }

            $url = $this->url . '/resumeSession.json';

            $data['uid'] = $this->uid;
            if (!isset($data->applicant)){
                $data['applicant'] = $this->applicant;
            }

            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];
            try {
                $response = static::sendRequest($url, $post);

                $data = \GuzzleHttp\json_decode($response)->data;
                $this->publicKey = $data->publicKey;
                $this->uid = $data->uid;

                return $response;
            } catch (\Exception $e) {
                return static::getError($e);
            }
        }


        protected function extractTokensFromLoginResponse($login)
        {
            return [$login->data->authToken, $login->data->reqToken];
        }
    }
}

