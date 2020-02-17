<?php

namespace System\Core;

use System\Exception\Handle;

class FileIncluder
{

    public function includeAllFiles($ROOT_DIR)
    {
        $this->includeAllSystemFiles($ROOT_DIR);
        $this->includeVendor();
        $this->includeExceptionFiles();
        $this->includeAllMvcFiles();
    }
    public function includeAllMvcFiles()
    {
        $mvcPaths = FileRepository::getMvcPaths();
        foreach ($mvcPaths as $key => $value) 
        {
            $this->includeAllPhpFileInFolder($value);
        }
    }

    public function includeAllSystemFiles($ROOT_DIR)
    {
        $this->includeAllPhpFileInFolder($ROOT_DIR."/System/Core");
        FileRepository::$ROOT_DIR = $ROOT_DIR;
        $this->includeAllPhpFileInFolder(FileRepository::$ROOT_DIR."/System/BaseMvc");
        $this->includeAllPhpFileInFolder(FileRepository::$ROOT_DIR."/System/Database");
    }

    private function includeExceptionFiles()
    {
        $this->includeAllPhpFileInFolder("../System/Exception");
        $handle = new Handle();
    }

    public function includeVendor()
    {
        require_once FileRepository::$ROOT_DIR."/vendor/autoload.php";
    }


    private function includeAllPhpFileInFolder($folder)
    {
        foreach (glob("{$folder}/*.php") as $filename)
        {
            require_once $filename;
        }
    }
}