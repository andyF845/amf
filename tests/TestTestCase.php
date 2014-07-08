<?php
define (AMF_BASE_PATH, '../amf/');
include_once '../amf/core.php';

class TestTestCase extends TestCase {
	function testAssertTrue() {
		$this->assertTrue(true);
		$this->assertTrue('a');
		$this->assertTrue(23);
	}
	function testAssertFalse() {
		$this->assertFalse(false);
		$this->assertFalse(null);
		$this->assertFalse('0');
		$this->assertFalse('');
	}
	function testAssertEqual() {
		$this->assertEqual(23, 23);
		$this->assertEqual('string', 'string');
	}
	function testAssertBetween() {
		$this->assertBetween(5, 0, 10);
		$this->assertBetween('c', 'a', 'z');
	}
}

$test = new TestTestCase();
$test->switchToHTMLOutput();
$test->run();
echo $test;
?>