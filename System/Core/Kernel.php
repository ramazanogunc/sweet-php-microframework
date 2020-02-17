<?php 

namespace System\Core;


use System\Exception\RouteException;

class Kernel
{
    private static $singleton = null;
    private function __construct()
    {
    }

    public static function getSingleton()
    {
        if (self::$singleton == null)
            self::$singleton = new Kernel();

        return self::$singleton;
    }

    public function run($server)
    {
        $requestParser = new RequestParser($server);

        if ($requestParser->hasRoute())
        {
            $controllerName = $requestParser->getRouteController();
            $methodName = $requestParser->getRouteMethod();
            $parameters = $requestParser->getUrlParams();
            $this->runController($controllerName, $methodName, $parameters);
        }
        else
        {
            throw new RouteException("{$server['REQUEST_METHOD']} \"{$server['REQUEST_URI']}\" Route not found");
            //$this->show404Page();
        }
    }

    private function runController($controller, $method, $params)
    {
        $controller = "\Mvc\Controller\\".$controller;
        if (class_exists($controller)) {
            $controller = new $controller;
            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], [$params]);
            } else {
                exit("Metod mevcut değil: {$method}");
            }
        } else {
            exit("Sınıf mevcut değil: {$controller}");
        }
    }

    private function show404Page()
    {
        http_response_code(404);
        header("HTTP/1.0 404 Not Found");
        require "../System/ErrorPages/404.html";
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }



}