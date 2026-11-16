<?php

class WeatherServiceException extends RuntimeException
{
    private $httpStatus;

    public function __construct($message, $httpStatus)
    {
        parent::__construct($message);
        $this->httpStatus = $httpStatus;
    }

    public function getHttpStatus()
    {
        return $this->httpStatus;
    }
}