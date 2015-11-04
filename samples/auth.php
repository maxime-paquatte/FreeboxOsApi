<?php
/**
 * Created by PhpStorm.
 * User: Maxime
 * Date: 04/11/2015
 * Time: 10:32
 */

define("APP_ID", "FreeboxApiSample");

define('__ROOT__', dirname(dirname(__FILE__)));
require_once __ROOT__ . "/src/class/FreeboxOsAuthService.php";
require_once __ROOT__ . "/src/class/DefaultCurlRequest.php";

//Create a DefaultCurlRequest, you can implement your own iJsonHttpRequest
$http = new DefaultCurlRequest("mafreebox.free.fr");

//Create the auth service, used to obtain the  session_token
$authService = new FreeboxOsAuthService($http);

//Challenge needed to obtain the session_token, can be obtains by authorize or login
$challenge = "";
$appToken = "";

//If you have never authorized your app
if(!file_exists(__ROOT__."/appToken.txt") ){

    //A message will be displayed on the Freebox LCD asking the user to grant/deny access to the requesting app.
    $r = $authService->authorize(APP_ID, "Freebox API Sample", "1.0", "userDeviceName");
    $appToken = $r["app_token"];
    $trackId = $r["track_id"];

    //Wait the user grant/deny or timeout
    while($challenge == ""){
        $p = $authService->authorizationProgress($trackId);

        if($p["status"] == "granted")//the app_token is valid and can be used to open a session
            $challenge = $p["challenge"];
        else if($p["status"] == "timeout")
            die("timeout, the user did not confirmed the authorization within the given time");
        else if($p["status"] == "denied")
            die("denied, the user denied the authorization request");
        else if($p["status"] == "unknown")
            die("unknown, the app_token is invalid or has been revoked");
        else if($p["status"] == "pending")
            echo "the user has not confirmed the authorization request yet\n";
        else die("Invalid status!");
        sleep (2);
    }

    //This is a sample, the token is secret and must be stored in a secured place
    file_put_contents ( __ROOT__."/appToken.txt", $appToken);
}
else{
    //If you already have an appToken, simply get a challenge with login function
    $appToken = file_get_contents(__ROOT__."/appToken.txt");
    $p = $authService->login();
    $challenge = $p["challenge"];
}

$r = $authService->openSession(APP_ID, $appToken,$challenge );
$sessionToken = $r["session_token"];

echo "Session token : ".$sessionToken. "\n";
foreach ($r["permissions"] as $key => $value){
   echo $key." -> ".$value . "\n";
}
