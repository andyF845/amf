<?php

define (TEST_APPLICATION_BAD_STRING,	"!this must be cut!");

class testApp extends Application {
	function validateString($name,$value) {
		return str_replace(TEST_APPLICATION_BAD_STRING, null, $value);	
	}
	public $str_string;
	public $str_invalidString;
	public $raw_raw;
	public $int_int;
	public $int_invalidInt;
}

class TestApplication extends TestCase {
	function testPropertiesInit() {
		$params = array('string'=>'abc string','raw'=>'some unchanged data','int'=>23,'invalidString'=>TEST_APPLICATION_BAD_STRING,'invalidInt'=>'abc'); 
		$app = new testApp($params);
		$this->assertEqual($app->str_string, $params['string']);
		$this->assertEqual($app->str_invalidString, '');
		$this->assertEqual($app->raw_raw, $params['raw']);
		$this->assertEqual($app->int_int, $params['int']);
		$this->assertEqual($app->int_invalidInt, 0);
	}
}

$test = new TestApplication();
$test->switchToHTMLOutput();
$test->run();
echo $test;
?>