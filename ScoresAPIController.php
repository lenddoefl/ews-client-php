<?php
namespace {
    include_once 'EWSMain.php';
}

namespace EFLGlobal\EWSClient
{
    class ScoresAPIController extends EWSMain
    {
        public function callDateQuery ($date)
        {
            $url = $this->url . '/dateQuery.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "dateQuery"=>$date
            ];
            try {
                $response = self::sendRequest($url, $post);
                return $response;
            } catch (\Exception $e) {
                return self::getError($e);
            }
        }

        public function callSubject ($subject)
        {
            $url = $this->url . '/subject.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "subjects"=>$subject
            ];
            try {
                $response = self::sendRequest($url, $post);
                return $response;
            } catch (\Exception $e) {
                return self::getError($e);
            }
        }
    }
}



