<?php

require_once "iJsonHttpRequest.php";
require_once "FreeboxOsServiceBase.php";

class FreeboxOsAuthService extends FreeboxOsServiceBase
{
    public function __construct(iJsonHttpRequest $httpRequest)
    {
        parent::__construct($httpRequest);
    }

    /**
     * A message will be displayed on the Freebox LCD asking the user to grant/deny access to the requesting app.
     * remark: You must persist the returned app_token
     * @return array whose contains : app_token, track_id
     * @throws Exception
     * @throws FreeboxOsFailedResponse
     */
    public function authorize($appId, $appName, $appVersion, $deviceName)
    {
        return $this->httpPost('/api/v3/login/authorize/',  [
            'app_id' => $appId,
            'app_name' => $appName,
            'app_version' => $appVersion,
            'device_name' => $deviceName
        ]);
    }


    /**
     * Once the authorization request has been made, monitor the token status using the track_id returned by authorize method.
     * @param $trackId
     * @return array whose contains : status, challenge
     * @throws Exception
     * @throws FreeboxOsFailedResponse
     */
    public function authorizationProgress($trackId)
    {
        return $this->httpGet("/api/v3/login/authorize/" . $trackId);
    }

    /**
     * Get a new challenge
     * @return array whose contains : logged_in, challenge
     * @throws Exception
     * @throws FreeboxOsFailedResponse
     */
    public function login()
    {
        return $this->httpGet("/api/v3/login/");
    }

    /**
     * Opening a session with the challenge (obtains by authorizationProgress or login) and the app_token (obtains by authorize)
     * @param $appId
     * @param $appToken
     * @param $challenge
     * @return array whose contains : session_token, challenge, permissions
     */
    public function openSession($appId, $appToken, $challenge)
    {
        $password = hash_hmac('sha1', $challenge, $appToken);

        $json = $this->httpPost("/api/v3/login/session/", [
            'app_id' => $appId,
            'password' => $password
        ]);
        $this->sessionToken = $json->session_token;
        return $json;
    }

    /**
     * close the current session
     * @throws Exception
     * @throws FreeboxOsFailedResponse
     */
    public function logout()
    {
        $this->httpPost("/api/v3/login/logout/");
    }

}