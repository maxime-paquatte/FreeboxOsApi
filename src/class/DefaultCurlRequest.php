<?php

/**
 * Created by PhpStorm.
 * User: Maxime
 * Date: 04/11/2015
 * Time: 10:33
 */

require_once "iJsonHttpRequest.php";

class DefaultCurlRequest implements iJsonHttpRequest
{
    public function __construct($urlBase)
    {
        $this->urlBase = $urlBase;
    }

    public function get($relUrl, $headers  =array() )
    {
        $h = array();
        foreach ($headers as $key => $value){
            array_push($h,  $key.": ".$value );
        }

        $ch = curl_init($this->urlBase.$relUrl);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => $h
        ));
        $response = curl_exec($ch);
        if($response === FALSE) die(curl_error($ch));
        return json_decode($response);
    }

    public function post($relUrl, $content, $headers =array())
    {
        $h = array('Content-Type: application/json; charset=utf-8');
        foreach ($headers as $key => $value){
            array_push($h,  $key.": ".$value );
        }

        $json = json_encode($content );
        $ch = curl_init($this->urlBase.$relUrl);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => $h,
            CURLOPT_POSTFIELDS => $json
        ));
        $response = curl_exec($ch);
        if($response === FALSE) die(curl_error($ch));

        return json_decode($response);
    }

    public function put($relUrl, $content, $headers =array())
    {
        $h = array('Content-Type: application/json; charset=utf-8');
        foreach ($headers as $key => $value){
            array_push($h,  $key.": ".$value );
        }


        $json = json_encode($content );
        $ch = curl_init($this->urlBase.$relUrl);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => $h,
            CURLOPT_POSTFIELDS => $json
        ));
        $response = curl_exec($ch);
        if($response === FALSE) die(curl_error($ch));

        return json_decode($response);
    }
}