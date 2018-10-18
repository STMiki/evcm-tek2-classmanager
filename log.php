<?php

function printLog($from, $message, $isException = false) {

	$_dirLog = '/var/www/html/log/';

	if ($type == false)
		$filename = 'Log_';
	else
		$filename = 'Exception_';

	$filename .= date('Y:m:d-H:i:s').'_';
	$filename .= $from.'.txt';

	file_put_contents($_dirLog.$filename, $message);
}
