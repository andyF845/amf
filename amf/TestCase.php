<?php

define (TEST_CASE_ASSERTION_FAILED,			'Assertion failed.');
define (TEST_CASE_EXCEPTION_CAUGHT,			'Exception caught.');
define (TEST_CASE_TEST_NOT_RUN,				'Run the test first.');
define (TEST_CASE_DEFAULT_PASS_STRING,		'PASS');
define (TEST_CASE_DEFAULT_FAIL_STRING,		'FAIL');
define (TEST_CASE_DEFAULT_NL,				"");
define (TEST_CASE_DEFAULT_BEGIN_MESSAGE,	'Test started.');
define (TEST_CASE_DEFAULT_END_MESSAGE,		'Test finished.');
define (TEST_CASE_HTML_OUT_NL,				"<br>");
define (TEST_CASE_HTML_OUT_PASS,			"<span style=\"font-weight:bold; color:green;\">PASS</span>");
define (TEST_CASE_HTML_OUT_FAIL, 			"<span style=\"font-weight:bold; color:red;\">FAIL</span>");


/**
 *  AssertionFail esxception class
 */
class ExceptionAssertionFail extends Exception {};

/**
 *  TestCase strategy base class
 */
class TestCase {
	public $pass			= TEST_CASE_DEFAULT_PASS_STRING;
	public $fail			= TEST_CASE_DEFAULT_FAIL_STRING;
	public $EOL				= EOL;
	public $NL				= TEST_CASE_DEFAULT_NL;
	public $onBeginMessage	= TEST_CASE_DEFAULT_BEGIN_MESSAGE;
	public $onEndMessage	= TEST_CASE_DEFAULT_END_MESSAGE;
	public $isOk 			= false;
	private  $startTime;
	private  $passCount;
	private  $testCount;
	private  $result;
	/**
	 *  Checks if $value is true
	 *  @throws ExceptionAssertionFail
	 */
	protected final function assertTrue($value) {
		if (!(bool) $value) throw new ExceptionAssertionFail();
	}
	/**
	 *  Checks if $value is false
	 */
	protected final function assertFalse($value) {
		$this->assertTrue(!(bool) $value);
	}
	/**
	 *  Checks if $value1 == $value2
	 */	
	protected final function assertEqual($value1, $value2) {
		$this->assertTrue($value1 == $value2);
	}
	/**
	 *  Checks if $minValue < $value < $maxValue
	 */
	protected final function assertBetween($value, $minValue, $maxValue) {
		$this->assertTrue( ($minValue < $value) && ($value < $maxValue) );
	}
	/**
	 *  Returns duration from $start til now
	 */
	protected final function getDuration($start) {
		return round(microtime(true)-$start,4)." s";
	}
	/**
	 *  Adds $message and $coment to $result property
	 */
	public function addResultLine($message, $coment = null) {
		$message .= $coment? " - ".$coment : null;
		$this->result .= !$this->result? null : $this->NL;
		$this->result .= sprintf("[%s] %s%s",date('H:i:s'),$message,$this->EOL);
	}
	/**
	 *  Method, called if test is passed
	 */
	protected function onPass($msg) {
		$this->passCount++;
		$this->testCount++;
		$this->addResultLine($this->pass,$msg);
	}
	/**
	 *  Method, called if test is failed
	 */
	protected function onFail($msg) {
		$this->testCount++;
		$this->addResultLine($this->fail,$msg);
	}
	/**
	 *  Method, called on TestCase begin
	 */
	protected function onBegin() {
		$this->startTime = microtime(true);
		$this->addResultLine($this->onBeginMessage.date('d-M'));
	}
	/**
	 *  Method, called on TestCase end
	 */
	protected function onEnd() {
		$passRate = ($this->testCount != 0)? round($this->passCount/($this->testCount)*100,2) : 0;
		$testDuration = $this->getDuration($this->startTime);
		$resultMessage = $this->passCount."/".$this->testCount.", $passRate%, $testDuration";
		if ($this->testCount == $this->passCount) {
			$this->onPass($resultMessage);
			$this->isOk = true; 
		} else {
			$this->onFail($resultMessage);
			$this->isOk = false;
		}
		$this->addResultLine($this->onEndMessage.date('d-M'));
	}
	/**
	 * Force object to generate HTML output.
	 */
	public final function switchToHTMLOutput() {
		$this->fail = TEST_CASE_HTML_OUT_FAIL;
		$this->pass = TEST_CASE_HTML_OUT_PASS;
		$this->NL = TEST_CASE_HTML_OUT_NL;
	}
	/**
	 * Force object to generate plain text output.
	 */
	public final function switchToPlainOutput() {
		$this->fail = TEST_CASE_DEFAULT_FAIL_STRING;
		$this->pass = TEST_CASE_DEFAULT_PASS_STRING;
		$this->NL = TEST_CASE_DEFAULT_NL;
	}	
	/**
	 *  Strategy method of the TestCase class.
	 */	
	public final function run() {
		$this->onBegin() ;
		$tests = get_class_methods( get_class( $this ) );
		$notTests = get_class_methods( 'TestCase' );
		$test = array_diff($tests, $notTests);
		foreach($test as $test) {
			try {
				$start = microtime(true);
				$this->$test();
				$this->onPass($test.", ".$this->getDuration($start));
			}
			catch (ExceptionAssertionFail $e){
				$this->onFail($test." ".TEST_CASE_ASSERTION_FAILED);
			}
			catch (Exception $e) {
				$this->onFail($test." ".TEST_CASE_EXCEPTION_CAUGHT." (".$e->getMessage().")");
			}
		}
		$this->onEnd('Test end');
	}
	/**
	 *  Object to string conversion
	 */
	function __toString() {
		return $this->result?: TEST_CASE_TEST_NOT_RUN;
	}
}
?>