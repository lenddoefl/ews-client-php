<?php
namespace {
    include_once 'EWSPMain.php';
}

namespace EWSPHPClient
{
    class ScoresController extends EWSPMain
    {
        public function callDateQuery ($date)
        {
            $url = $this->url . 'dateQuery.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "dateQuery"=>$date
            ];
            try {
                $response = self::sendRequest($url, $post);
                return $response;
            } catch (\Exception $e) {
                return var_dump($e);
            }
        }

        public function callSubject ($subject)
        {
            $url = $this->url . 'subject.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "subjects"=>$subject
            ];
            try {
                $response = self::sendRequest($url, $post);
                return $response;
            } catch (\Exception $e) {
                return var_dump($e);
            }
        }
    }
}



