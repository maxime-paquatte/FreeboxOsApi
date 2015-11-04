<?php

require_once "iJsonHttpRequest.php";
require_once "FreeboxOsServiceBase.php";

class FreeboxOsDownloadService extends FreeboxOsServiceBase
{
    public function __construct(iJsonHttpRequest $httpRequest, $sessionToken)
    {
        parent::__construct($httpRequest, $sessionToken);
    }

    /**
     * @param $mode : THROTTLING_MODE_NORMAL, THROTTLING_MODE_SLOW, THROTTLING_MODE_HIBERNATE, THROTTLING_MODE_SCHEDULE
     * @return bool
     * @throws Exception
     * @throws FreeboxOsFailedResponse
     */
    public function setMode($mode)
    {
        $modes = array("normal", "slow", "hibernate", "schedule");
        if (!in_array($mode, $modes)) throw new Exception("Invalid mode, must be one of: " . implode(", ", $modes));

        return $this->httpPut('/api/v3/downloads/throttling',['throttling' => $mode] );
    }
}

define ('THROTTLING_MODE_NORMAL', "normal");
define ('THROTTLING_MODE_SLOW', "slow");
define ('THROTTLING_MODE_HIBERNATE', "hibernate");
define ('THROTTLING_MODE_SCHEDULE', "schedule");

