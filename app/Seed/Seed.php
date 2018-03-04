<?php

namespace Avir\Seed;

abstract class Seed
{
    const   MY_IP = "188.162.77.218",
            MY_HOST = "askiz.tk.loc",
            SRVER_IP = "31.41.216.88";
    public  $requestUri,
            $method;

    public function getInfo()
    {
        $this->requestUri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];

    }
}