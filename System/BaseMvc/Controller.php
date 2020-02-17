<?php

namespace System\BaseMvc;

use mysql_xdevapi\DatabaseObject;
use System\Core\FileRepository;
use System\Core\Kernel;

abstract class Controller
{
    private $twig;

    public function __construct() {
        $viewFolder = FileRepository::getMvcPaths()->__View__;
        $loader = new \Twig\Loader\FilesystemLoader($viewFolder);
        $this->twig = new \Twig\Environment($loader /*, [
            'cache' => $viewFolder."/_cache",
        ]*/);
    }

    protected function render($viewPath,$data = null )
    {
        if (is_null($data))
            echo $this->twig->render($viewPath.".html.twig");
        else
            echo $this->twig->render($viewPath.".html.twig",$data);
    }

    protected function redirect($url)
    {
        header("Location: ".$url);
    }
}
