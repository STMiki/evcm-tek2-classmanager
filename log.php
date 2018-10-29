<?php

final class Log {

    private $message_log = '';
    private $message_exception = '';

    const DIR_LOG = '/var/www/html/class/log/';
    const DIR_EXCEPTION = '/var/www/html/class/log/';

    public function __destruct()
    {
        $this->printLog();
    }

    public function __invoke($from, $message, $isException = false)
    {
        $this->printLog($from, $message, $isException);
    }

    public function stockLog($from, $message, $isException = false)
    {
        $message = '['.$from.']: '.str_replace('\n', PHP_EOL.'\t', str_replace(PHP_EOL, '\n',$message)).PHP_EOL;
        if ($isException)
            $this->message_exception .= $message;
        else
            $this->message_log .= $message;
    }

    public function printLog()
    {
        $filename = date('Y-m-d_H:i:s').'.txt';

        if (!empty($this->message_log))
            file_put_contents(SELF::DIR_LOG.'Log_'.$filename, $this->message_log);
        if (!empty($this->message_exception))
            file_put_contents(SELF::DIR_EXCEPTION.'Exception_'.$filename, $this->message_exception);
    }
}

function printLog($from, $message, $isException = false)
{
    $GLOBALS['log_class']->stockLog($from, $message, $isException);
}

function realPrintLog()
{
    try {
        $GLOBALS['log_class']->__destruct();
    }
}

$log_class = new Log();

register_shutdown_function('realPrintLog');
