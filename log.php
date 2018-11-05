<?php

final class Log {

    private $message_log = Array();

    const DIR_LOG = '/var/www/html/class/log/';
    const DIR_JSON = '/var/www/html/class/log/';
    const INFO = 0;
    const EXCEPTION = 1;
    const WARNING = 2;

    public function __destruct()
    {
        $this->printLog();
    }

    public function __invoke($from, $message, $isException = SELF::INFO)
    {
        $this->printLog($from, $message, $isException);
    }

    public function stockLog($from, $message, $isException = SELF::INFO)
    {
        $data = Array();

        if ($isException == 1)
            $data['type'] = 'Exception';
        else if ($isException == 2)
            $data['type'] = 'Warning';
        else
            $data['type'] = 'Info';

        $data['origin'] = $from;
        $data['message'] = str_replace('\n', PHP_EOL."\t", str_replace(PHP_EOL, '\n',$message));;

        $this->message_log[] = $data;
    }

    private function printLog()
    {
        $message = '';

        foreach ($this->message_log as $value) {
            $message .= "[{$value['type']}] ({$value['origin']}) : {$value['message']}\n";
        }
        $filename = 'Log_'.date('Y-m-d_H-i-s');

        file_put_contents(SELF::DIR_LOG.$filename.'.txt', $message);
        file_put_contents(SELF::DIR_LOG.$filename.'.json', json_encode($this->message_log));
    }
}

function printLog($from, $message, $isException = 0)
{
    $GLOBALS['log_class']->stockLog($from, $message, $isException);
}

function realPrintLog()
{
    try {
        $GLOBALS['log_class']->__destruct();
    } finally {}
}

$log_class = new Log();

register_shutdown_function('realPrintLog');
