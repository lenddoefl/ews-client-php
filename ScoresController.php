<?php
namespace {
    include_once 'EWSPMain.php';
}

namespace EWSPHPClient
{
    class ScoresController extends EWSPMain
    {
        protected function callDateQuery ($date)
        {
            $url = $this->url . 'dateQuery.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "dateQuery"=>$date
            ];
            $response = self::sendRequest($url, $post);
            return $response;
        }

        protected function callSubject ($subject)
        {
            $url = $this->url . 'subject.json';
            $post = [
                "authToken"=>  $this->authToken64,
                "reqToken"=>   $this->reqToken64,
                "subjects"=>$subject
            ];
            $response = self::sendRequest($url, $post);
            return $response;
        }
    }
}



