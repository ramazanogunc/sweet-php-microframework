<?php

$ROOT_DIR = dirname(__DIR__) ;
require $ROOT_DIR."/System/Core/FileIncluder.php";

$fileInclude = new System\Core\FileIncluder();
$fileInclude->includeAllFiles($ROOT_DIR);

$kernel = \System\Core\Kernel::getSingleton();
$kernel->run($_SERVER);


