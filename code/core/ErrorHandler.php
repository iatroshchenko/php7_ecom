<?php


namespace Core;

use Throwable;

class ErrorHandler
{
    public function __construct()
    {
        $logFile = STORAGE . '/logs/error.log';
        if(!is_file($logFile)) file_put_contents($logFile, 'This is an error log file' . PHP_EOL);

        $level = E_ALL;
        error_reporting($level);
        set_error_handler([
            $this,
            'handleError'
        ], $level);
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

    public function handleException(Throwable $e)
    {
        $this->log($e->getFile(), $e->getLine(), $e->getMessage());
        $this->display(
            '', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode(), get_class($e)
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

    private function display ($errno, $errorText, $errorFile, $errorLine, $responseCode = 404, $errorClass='')
    {
//        if ($responseCode == 404 && !DEBUG) {
        if ($responseCode == 404) {
            require ERROR_TEMPLATES . '/404.php';
        }
        if (DEBUG) {
            require ERROR_TEMPLATES . '/dev.php';
        } else {
            require ERROR_TEMPLATES . '/prod.php';
        }
    }
}