<?php

/**
 * Created by PhpStorm.
 * User: Maxime
 * Date: 04/11/2015
 * Time: 12:01
 */
class FreeboxOsDownloadService
{
    private $httpRequest;
    private $server;
    private $sessionToken;
    private $header;

    public function __construct(iJsonHttpRequest $httpRequest, $sessionToken)
    {
        $this->httpRequest = $httpRequest;
        $this->sessionToken = $sessionToken;

        $this->header = array('X-Fbx-App-Auth' => $sessionToken);
    }

    /**
     * @param $mode : THROTTLING_MODE_NORMAL, THROTTLING_MODE_SLOW, THROTTLING_MODE_HIBERNATE, THROTTLING_MODE_SCHEDULE
     * @return bool
     * @throws Exception
     */
    public function setMode($mode)
    {
        $modes = array("normal", "slow", "hibernate", "schedule");
        if (!in_array($mode, $modes)) throw new Exception("Invalid mode, must be one of: " . implode(", ", $modes));

        $json = $this->httpRequest->put('/api/v3/downloads/throttling',
            array('throttling' => $mode), $this->header
        );

        if (!$json['success'])
            throw new Exception($json['error_code'] . ': ' . $json['msg']);

        return true;
    }
}

define ('THROTTLING_MODE_NORMAL', "normal");
define ('THROTTLING_MODE_SLOW', "slow");
define ('THROTTLING_MODE_HIBERNATE', "hibernate");
define ('THROTTLING_MODE_SCHEDULE', "schedule");

