<?php


define("APP_ID", "FreeboxApiSample");

define('__ROOT__', dirname(dirname(__FILE__)));
require_once __ROOT__ . "/src/class/DefaultCurlRequest.php";
require_once __ROOT__ . "/src/class/FreeboxOsAuthService.php";
require_once __ROOT__ . "/src/class/FreeboxOsDownloadService.php";

//To obtains the $sessionToken see Samples/Auth.php
$http = new DefaultCurlRequest("mafreebox.free.fr");

$authService = new FreeboxOsAuthService($http);
$appToken = file_get_contents(__ROOT__."/appToken.txt");
$challenge = $authService->login()->challenge;
$sessionToken = $authService->openSession(APP_ID, $appToken,$challenge )->session_token;

$downloadService = new FreeboxOsDownloadService($http, $sessionToken);
$downloadService->setMode(THROTTLING_MODE_NORMAL);

$authService->logout();