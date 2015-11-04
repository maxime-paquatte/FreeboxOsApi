<?php

/**
 * Interface iJsonHttpRequest
 * serialize/deserialize json and send request
 */
interface iJsonHttpRequest
{
    /**
     * @param $relativeUrl : the relative url
     * @param $headers : key => value for http headers
     * @return object: json response deserialized
     */
    public function get($relativeUrl, $headers);

    /**
     * @param $relativeUrl : the relative url
     * @param $content : object to serialize and passe to the request
     * @param $headers : key => value for http headers
     * @return object: json response deserialized
     */
    public function post($relativeUrl, $content, $headers);

    /**
     * @param $relativeUrl : the relative url
     * @param $headers : key => value for http headers
     * @param $content : object to serialize and passe to the request
     * @return object: json response deserialized
     */
    public function put($relativeUrl, $content, $headers);
}
