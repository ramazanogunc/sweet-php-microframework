<?php


namespace System\Exception;

use System\Core\FileRepository;

class Handle
{
    private $startTime = 0;
    private $debugMode = false;

    function __construct()
    {
        $this->startTime = microtime(true);
        ob_start();
        ini_set("display_errors", "on");
        error_reporting(E_ALL);
        set_error_handler(array($this, 'scriptError'));
        set_exception_handler(array($this, 'exceptionError'));
        register_shutdown_function(array($this, 'shutdown'));
        $this->debugMode = FileRepository::getGeneralInfo()->debugMode;
    }

    function scriptError($errno, $errstr, $errfile, $errline)
    {
        $errorArray = array();

        switch($errno)
        {
            case E_ERROR:               $errseverity = "Fatal Error";       break;
            case E_WARNING:             $errseverity = "Warning";           break;
            case E_NOTICE:              $errseverity = "Notice";            break;
            case E_CORE_ERROR:          $errseverity = "Core Error";        break;
            case E_CORE_WARNING:        $errseverity = "Core Warning";      break;
            case E_COMPILE_ERROR:       $errseverity = "Compile Error";     break;
            case E_COMPILE_WARNING:     $errseverity = "Compile Warning";   break;
            case E_USER_ERROR:          $errseverity = "User Error";        break;
            case E_USER_WARNING:        $errseverity = "User Warning";      break;
            case E_USER_NOTICE:         $errseverity = "User Notice";       break;
            case E_STRICT:              $errseverity = "Strict Standards";  break;
            case E_RECOVERABLE_ERROR:   $errseverity = "Recoverable Error"; break;
            case E_DEPRECATED:          $errseverity = "Deprecated";        break;
            case E_USER_DEPRECATED:     $errseverity = "User Deprecated";   break;
            default:                    $errseverity = "Error";             break;
        }

        $errorArray["type"] = $errseverity;

        $v = debug_backtrace();
        $errorArray['traces'] = $v;
        $errorArray['message'] = $errstr;
        $errorArray['line'] = $errline;
        $errorArray['file'] = $errfile;
        $errorArray['readedFile'] = $this->fileRead($errfile,$errline);
        $this->showError($errorArray);

    }

    function exceptionError($exception)
    {
        $array = array();
        $array['message'] = $exception->getMessage();
        $array['file'] = $exception->getFile();
        $array['line'] = $exception->getLine();
        $array['readedFile'] = $this->fileRead($array['file'],$array['line']);

        if (get_class($exception) == "System\Exception\RouteException" && $this->debugMode === false)
        {
            $this->show404();
        }
        else
        {
            $this->showException($array);

        }
    }

    private function fileRead($fileName, $line)
    {
        $file = new \SplFileObject($fileName);

        $readed = array();


        for ($i = $line-5; $i<$line+5; $i++)
        {
            $file->seek($i);
            $readed[] = $file->current();
        }
        return $readed;
    }

    function shutdown()
    {
        $isError = false;
        if ($error = error_get_last())
        {
            switch($error['type'])
            {
                case E_ERROR:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                case E_RECOVERABLE_ERROR:
                case E_CORE_WARNING:
                case E_COMPILE_WARNING:
                    $isError = true;
                    $this->scriptError($error['type'], $error['message'], $error['file'], $error['line']);
                    break;
            }
        }
    }

    private function initTwig()
    {
        $loader = new \Twig\Loader\FilesystemLoader(FileRepository::$ROOT_DIR."/System/ErrorPages");
        return  $twig = new \Twig\Environment($loader);
    }

    private function showError($errorArray)
    {
        if(!headers_sent())
            header("HTTP/1.1 500 Internal Server Error");
        if(ob_get_contents() !== false)
            ob_end_clean();


        if ($this->debugMode){
            $twig = $this->initTwig();
            echo $twig->render('debugV2.html.twig', $errorArray);
        }
        else{
            include FileRepository::$ROOT_DIR."/System/ErrorPages/500.html";
        }

    }

    private function showException($errorInfo)
    {
        if(!headers_sent())
            header("HTTP/1.1 500 Internal Server Error");
        if(ob_get_contents() !== false)
            ob_end_clean();



        if ($this->debugMode){
            $twig = $this->initTwig();
            echo $twig->render('exception.html.twig', $errorInfo);
        }
        else{
            include FileRepository::$ROOT_DIR."/System/ErrorPages/500.html";
        }
    }

    private function show404()
    {
        http_response_code(404);
        header("HTTP/1.0 404 Not Found");
        require FileRepository::$ROOT_DIR."/System/ErrorPages/404.html";
    }
}