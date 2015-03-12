<?php

class RedmineApi {

    var $url;
    var $apiKey;
    var $accessUsername;
    var $accessPassword;

    public function __construct($url, $apiKey, $accessUsername=null, $accessPassword=null) {
        $this->url = $url;
        $this->apiKey = $apiKey;
        $this->accessUsername = $accessUsername;
        $this->accessPassword = $accessPassword;
    }

    function run($requet, $method, $body='') {

        $curl = curl_init($this->url.$requet);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        $httpheader = array(
            'Content-Type: application/json',
            'X-Redmine-API-Key: '.$this->apiKey,
        );
        if (!empty($this->accessUsername) && !empty($this->accessPassword) {
            $httpheader[] = 'Authorization: Basic '.base64_encode($this->accessUsername.':'.$this->accessPassword);      
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);
        switch($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
                break;
            case 'GET':
            default:
                break;
        }
        $result = curl_exec($curl);
        return $result;
    }

    function get($request) {
        return $this->run($request, 'GET');
    }

    function put($request, $body) {
        return $this->run($request, 'PUT', $body);
    }

    function post($request, $body) {
        return $this->run($request, 'POST', $body);
    }

    function delete($request) {
        return $this->run($request, 'DELETE');
    }

}
