<?php


namespace System\BaseMvc;


abstract class ApiController
{

    protected function responseCode($headCode)
    {
        http_response_code($headCode);
    }
    
    protected function render($jsonData)
    {
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($jsonData);
    }

}