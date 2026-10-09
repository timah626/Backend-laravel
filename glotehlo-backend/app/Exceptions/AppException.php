<?php

namespace App\Exceptions;

class AppException extends \RuntimeException
{
    public string $errorCode;//code message 
    public int $httpStatus; //code 

    public function __construct(
        string $errorCode,
        string $message, //message yo wanna say
        int $httpStatus
    ) {
        $this->errorCode = $errorCode;
        $this->httpStatus = $httpStatus;

        parent::__construct($message); //call the construction of the parent class constructor and pass the message . cause this class already knows how to you know pass an errror message 
    }
}