<?php
/**
 * CacheProviders test.
 * TestCache class is able to test any class, that implements CacheProvider interface.
 */
define (AMF_BASE_PATH, '../amf/');
include_once '../amf/core.php';

class TestPageBuilder extends TestCase {
	function test() {
		//Creating new pagebuilder based on template stored in file
		$pb = new PageBuilder('./test/testPageBuilder.txt');
		//Adding data fields. Do not change these separately from testPageBuilderOut.txt! 
		$pb->setField('text', 'Test text. Проверка.');
		$pb->setField('some data', "Какие-то данные.");
		//Build the page
		$page = $pb->buildPage();
		//Loading reference content
		$ref = file_get_contents('./test/testPageBuilderOut.txt');
		//Check if PageBuilder gave the save result as we have in reference.
		$this->assertEqual($page, $ref);
	} 
}

$test = new TestPageBuilder();
$test->switchToHTMLOutput();
$test->run();
echo $test;

?>