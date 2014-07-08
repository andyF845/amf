<?php
//AMF global constants
define (AMF_CORE_VERSION,	"v0.1-alpha");
define (AMF_BASE_PATH,		"./amf/");
define (EOL, 				"\r\n" );

function __autoload($className) {
	$className = AMF_BASE_PATH.$className.'.php';
	if (!file_exists($className))
		return false;
	include_once $className; 
		return true; 
} 

?>