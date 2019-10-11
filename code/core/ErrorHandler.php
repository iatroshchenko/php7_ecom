<?php


namespace Core;

class ErrorHandler
{
    public function __construct()
    {
        error_reporting(E_ALL);
        set_error_handler(
            [$this, 'handleError'],
            E_ALL
        );
        set_exception_handler([
            $this,
            'handleException'
        ]);
    }

    public function handleError($errno, $msg, $file, $line)
    {
        $this->log($file, $line, $msg);
        $this->display(
            $errno, $msg, $file, $line, 500
        );
    }

    public function handleException(\Error $e)
    {
        $this->log($e->getFile(), $e->getLine(), $e->getMessage());
        $this->display(
            'Some num', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode()
        );
    }

    private function log ($file = '', $line = '', $message = '')
    {
        $path_to_logfile = STORAGE . '/logs/error.log';
        error_log(
            '['. date('Y-m-d H:i:s') . ']' . 'Error text: ' . $message . '| File: ' . $file . ' | Line: ' . $line . PHP_EOL,
            3,
            $path_to_logfile
        );
    }

    private function display ($errorCode, $errorText, $errorFile, $errorLine, $responseCode = 404)
    {
        http_response_code($responseCode);
        if ($responseCode == 404 && !DEBUG) {
            require ERROR_TEMPLATES . '/404.php';
        }
        if (DEBUG) {
            require ERROR_TEMPLATES . '/dev.php';
        } else {
            require ERROR_TEMPLATES . '/prod.php';
        }
    }
}