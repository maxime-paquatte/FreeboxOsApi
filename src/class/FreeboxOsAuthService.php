<?php

require_once "iJsonHttpRequest.php";

class FreeboxOsAuthService
{
    public function __construct(iJsonHttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }

    /**
     * A message will be displayed on the Freebox LCD asking the user to grant/deny access to the requesting app.
     * remark: You must persist the returned app_token
     * @return array whose contains : app_token, track_id
     * @throws Exception
     */
    public function authorize($appId, $appName, $appVersion, $deviceName)
    {
        $json = $this->httpRequest->post('/api/v3/login/authorize/',  array(
            'app_id' => $appId,
            'app_name' => $appName,
            'app_version' => $appVersion,
            'device_name' => $deviceName
        ));
        if (!$json['success'])
            throw new Exception($json['error_code'] . ': ' . $json['msg']);

        return $json['result'];
    }


    /**
     * Once the authorization request has been made, monitor the token status using the track_id returned by authorize method.
     * @param $trackId
     * @return array whose contains : status, challenge
     * @throws Exception
     */
    public function authorizationProgress($trackId)
    {
        $json = $this->httpRequest->get("/api/v3/login/authorize/" . $trackId);
        if (!$json['success'])
            throw new Exception($json['error_code'] . ': ' . $json['msg']);

        return $json['result'];
    }

    /**
     * Get a new challenge
     * @return array whose contains : logged_in, challenge
     * @throws Exception
     */
    public function login()
    {
        $json = $this->httpRequest->get("/api/v3/login/");

        if (!$json['success'])
            throw new Exception($json['error_code'] . ': ' . $json['msg']);

        return $json['result'];
    }

    /**
     * Opening a session with the challenge (obtains by authorizationProgress or login) and the app_token (obtains by authorize)
     * @param $appToken
     * @param $challenge
     * @return array whose contains : session_token, challenge, permissions
     * @throws Exception
     */
    public function openSession($appId, $appToken, $challenge)
    {
        $password = hash_hmac('sha1', $challenge, $appToken);

        $json = $this->httpRequest->post("/api/v3/login/session/", array(
            'app_id' => $appId,
            'password' => $password
        ));

        if (!$json['success'])
            throw new Exception($json['error_code'] . ': ' . $json['msg']);

        return $json['result'];
    }

    /**
     * close the current session
     * @throws Exception
     */
    public function logout()
    {
        $json = $this->httpRequest->post("/api/v3/login/logout/");
        if (!$json['success'])
            throw new Exception($json['error_code'] . ': ' . $json['msg']);
    }

}