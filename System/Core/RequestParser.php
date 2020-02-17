<?php

namespace System\Core;

use System\BaseMvc\Route;

class RequestParser
{
    private $requestMethod;
    private $urlPath;
    private $urlParams;
    private $controller;
    private $method;


    public function __construct($server)
    {
        $this->requestMethod = $server['REQUEST_METHOD'];
        $this->urlPath = $server['REQUEST_URI'];
    }

    public function hasRoute()
    {
        return $this->parseUrl();
    }

    public function getRouteController()
    {
        return $this->controller;
    }

    public function getRouteMethod()
    {
        return $this->method;
    }

    public function getUrlParams()
    {
        return $this->urlParams;
    }

    private function parseUrl()
    {
        $this->urlPath = $this->parsePath($this->urlPath);
        return $this->decideRoute();
    }

    private function decideRoute()
    {
        foreach (Route::$routes[$this->requestMethod] as $path => $controller)
        {
            $tempRoutePath = $this->parsePath($path);

            if (count($tempRoutePath) == count($this->urlPath))
            {
                if ($this->matchRequestAndRoute($this->urlPath,$tempRoutePath))
                {
                    $controllerArray = explode("@",$controller);
                    $this->controller = $controllerArray[0];
                    $this->method = $controllerArray[1];
                    //$this->runController($controller);
                    return true;
                }

            }
        }
        return false;
    }

    private function matchRequestAndRoute($request,$route)
    {
        $parameters = array();
        $check = false;
        for ($i = 0;$i<count($request);$i++)
        {
            if (isset($route[$i][0]) && $route[$i][0] == "{")
            {
                $key = ltrim($route[$i],"{");
                $key = rtrim($key, "}");
                $parameters += array($key=>$request[$i]);
                //echo "params:".$request[$i];
                $check = true;
            }
            elseif ($request[$i] == $route[$i])
            {
                $check = true;
            }
            else
            {
                $check = false;
                break;
            }
        }
        if ($check)
            $this->urlParams = $parameters;
        else
            $this->urlParams = null;

        return $check;
    }

    private function parsePath($string)
    {
        $string = ltrim($string,"/");
        return explode("/", $string);
    }

}