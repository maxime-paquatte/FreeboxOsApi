<?php

interface iJsonHttpRequest
{
    public function get($url, $headers);

    public function post($url, $content, $headers);

    public function put($url, $content, $headers);
}
