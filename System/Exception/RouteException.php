<?php


namespace System\Exception;


class RouteException extends \Exception
{
    public function __construct($message = "Route Not Found", $code = 0, Exception $previous = null) {


        parent::__construct($message, $code, $previous);

    }
}
