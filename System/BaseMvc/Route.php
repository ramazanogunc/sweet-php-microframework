<?php

namespace System\BaseMvc;

class Route
{
    public static $routes = array(
        "GET"=> array(),
        "POST" => array(),
        "PUT"=>array(),
        "DELETE"=>array()
    );

    public static function get(string $url, $classMethod)
    {
        self::$routes['GET'] += [$url => $classMethod];
    }

    public static function post(string $url, $classMethod)
    {
        self::$routes['POST'] += [$url => $classMethod];
    }
    public static function put(string $url, $classMethod)
    {
        self::$routes['PUT'] += [$url => $classMethod];
    }

    public static function delete(string $url, $classMethod)
    {
        self::$routes['DELETE'] += [$url => $classMethod];
    }


}
