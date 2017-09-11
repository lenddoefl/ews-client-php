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

        public function callStartSession ($data, $repeat=true)
        {
            try {
                if (isset($this->uid)) {
                    throw new \Exception($this->errorUidIsSet);
                }
            }
            catch (\Exception $e){
                return self::getError($e);
            }

            if ((!isset($this->authToken64)) or (!isset($this->reqToken64))){
                $this->callLogin();
            }

            $this->sequence = 0;
            $this->attachmentUids = [];

            $url = $this->url . '/startSession.json';

            if (isset($this->applicationHash)){
                $data['applicationHash'] = $this->applicationHash;
            }

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
                if ((strpos($e, "403 FORBIDDEN") !== false) and ($repeat===true)) {
                    $this->callLogin();
                    return $this->callResumeSession($data, false);
                }
                else {
                    return self::getError($e);
                }
            }
        }

        public function callFinishSession ($data = [])
        {
            try {
                if (!isset($this->uid)) {
                    throw new \Exception($this->errorUidNotSet);
                }
            }
            catch (\Exception $e){
                return self::getError($e);
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
                $response = self::sendRequest($url, $post);
                return $response;
            } catch (\Exception $e) {
                return self::getError($e);
            }
        }

        public function callCreateAttachment ($data)
        {
            try {
                if (!isset($this->uid)) {
                    throw new \Exception($this->errorUidNotSet);
                }
            }
            catch (\Exception $e){
                return self::getError($e);
            }

            $data['uid'] = $this->uid;

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
                return self::getError($e);
            }

        }

        public function callFinishStep ($data)
        {
            try {
                if (!isset($this->uid)) {
                    throw new \Exception($this->errorUidNotSet);
                }
            }
            catch (\Exception $e){
                return self::getError($e);
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
                $response = self::sendRequest($url, $post);
                $this->sequence++;

                return $response;
            } catch (\Exception $e) {
                return self::getError($e);
            }
        }

        public function callGetApplication ($data)
        {
            try {
                if (!isset($this->uid)) {
                    throw new \Exception($this->errorUidNotSet);
                }
            }
            catch (\Exception $e){
                return self::getError($e);
            }

            $url = $this->url . '/getApplication.json';

            $data['uid'] = $this->uid;

            if (!isset($data->uid)){
                $data['applicationHash'] = $this->applicationHash;
            }

            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "data"=>$data
            ];

            try {
                $response = self::sendRequest($url, $post);

                $data = \GuzzleHttp\json_decode($response)->data;
                $this->applicationHash = $data->applicationHash;

                return $response;
            } catch (\Exception $e) {
                return self::getError($e);
            }
        }

        public function callPrefetchApplications ($data, $repeat=true)
        {
            if ((!isset($this->authToken64)) or (!isset($this->reqToken64))){
                $this->callLogin();
            }

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
                if ((strpos($e, "403 FORBIDDEN") !== false) and ($repeat===true)) {
                    $this->callLogin();
                    return $this->callResumeSession($data, false);
                }
                else {
                    return self::getError($e);
                }
            }
        }

        public function callResumeSession ($data, $repeat=true)
        {
            try {
                if (!isset($this->uid)) {
                    throw new \Exception($this->errorUidNotSet);
                }
            }
            catch (\Exception $e){
                return self::getError($e);
            }

            if ((!isset($this->authToken64)) or (!isset($this->reqToken64))) {
                $this->callLogin();
            }

            $url = $this->url . '/resumeSession.json';

            $data['uid'] = $this->uid;

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
                if ((strpos($e, "403 FORBIDDEN") !== false) and ($repeat===true)) {
                    $this->callLogin();
                    return $this->callResumeSession($data, false);
                }
                else {
                    return self::getError($e);
                }
            }
        }
    }
}

