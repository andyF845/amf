<?php
define ( LOG_CRIT, 2 );
define ( LOG_WARNING, 4 );
define ( LOG_INFO, 6 );
define ( LOG_DEBUG, 7 );
class Logger {
	static $logLevel = LOG_INFO;
	static $debug = true;
	static $defaultLogFileName = "./amf/amf_log.log";
	static function log($message, $level, $logFileName = null) {
		if (self::$debug)
			echo $message;
		if ($level > self::$logLevel)
			exit ();
		try {
			$message = sprintf ( "[%s] %s %s%s", date ( 'd-M-y H:i:s ' ), $level, $message, EOL );
			$logFileName = $logFileName ?  : self::$defaultLogFileName;
			return file_put_contents ( $logFileName, $message, FILE_APPEND ) ? true : false;
		} catch ( Exception $e ) {
			return false;
		}
	}
}
?>