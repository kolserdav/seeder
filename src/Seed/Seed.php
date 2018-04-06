<?php

namespace Avir\Seed;

abstract class Seed
{
    const   MY_IP = "myIP",
            MY_HOST = "myHost",
            SRVER_IP = "serverIP";
    public  $requestUri,
            $method;

    public function getInfo()
    {
        $this->requestUri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];

    }
}