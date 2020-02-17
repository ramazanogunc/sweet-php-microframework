<?php

namespace System\Core;

class FileRepository
{
    private static $singleton = null;
    private $mvcPaths;
    private $databaseInfo;
    private $generalInfo;
    public static $ROOT_DIR;

    private function __construct() 
    {
    }

    private static function getInstance()
    {
        if (self::$singleton == null) {
            self::$singleton = new FileRepository();
        }
        return self::$singleton;
    }

    public static function getMvcPaths()
    {
        self::getInstance();
        $temp = json_decode(file_get_contents(self::$ROOT_DIR."/System/Config/MvcFolderPath.json"));
        foreach ($temp as $key => $value) {
            $temp->$key = self::$ROOT_DIR.$value;
        }
        self::$singleton->mvcPaths = $temp;
        return self::$singleton->mvcPaths;
    }

    public static function getDatabaseInfo()
    {
        $mvcPaths = self::getMvcPaths();
        self::$singleton->databaseInfo = json_decode(file_get_contents($mvcPaths->__Config__."/Database.json"));
        return self::$singleton->databaseInfo;
    }

    public static function getGeneralInfo()
    {
        $mvcPaths = self::getMvcPaths();
        self::$singleton->generalInfo = json_decode(file_get_contents($mvcPaths->__Config__."/General.json"));
        return self::$singleton->generalInfo;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
