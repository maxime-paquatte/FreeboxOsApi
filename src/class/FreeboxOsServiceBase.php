<?php

class FreeboxOsFailedResponse extends Exception
{
    public $errorCode;
    public $msg;

    public function __construct($errorCode, $msg, Exception $previous = null)
    {
        $this->errorCode = $errorCode;
        $this->msg = $msg;

        parent::__construct($errorCode . ': ' . $msg, 0, $previous);
    }
}

abstract class FreeboxOsServiceBase
{
    private $authHeader = "X-Fbx-App-Auth";

    protected $httpRequest;
    protected $sessionToken;

    protected function __construct(iJsonHttpRequest $httpRequest, $sessionToken = null)
    {
        $this->httpRequest = $httpRequest;
        $this->sessionToken = $sessionToken;
    }

    protected function httpGet($relUrl){
        $header = $this->sessionToken != null ? [$this->authHeader => $this->sessionToken] : [];
        $json = $this->httpRequest->get($relUrl, $header);

        return $this->getResult($json);
    }

    protected function httpPost($relUrl, $data){
        $header = $this->sessionToken != null ? [$this->authHeader => $this->sessionToken] : [];
        $json = $this->httpRequest->post($relUrl, $data, $header);

        return $this->getResult($json);
    }

    protected function httpPut($relUrl, $data){
        $header = $this->sessionToken != null ? [$this->authHeader => $this->sessionToken] : [];
        $json = $this->httpRequest->put($relUrl, $data, $header);

        return $this->getResult($json);
    }

    private function getResult($json){
        if (!$json->success)
            throw new FreeboxOsFailedResponse($json->error_code, $json->msg);
        return  $json->result;
    }
}