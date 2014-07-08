<?php
/**
 * Template engine test.
 */
define (AMF_BASE_PATH, '../amf/');
include_once '../amf/core.php';

class TestTemplates extends TestCase {
	function testPlainTextTemplate() {
		//Make template string
		$template = "This is an example of %templateEngine% usage.\n";
		$template .= "Today is %date%. We are running AMF %version%\n";
		
		//Make list of replaces
		$fields['templateEngine'] = Templater;
		$fields['date'] = date('c');
		$fields['version'] = AMF_CORE_VERSION;
		
		//Run template engine
		$result = Templater::makeText($template, $fields);
		
		//Check if result is not empty
		$this->assertTrue($result);
		
		//Build string for check
		$template = "This is an example of ".$fields['templateEngine']." usage.\n";
		$template .= "Today is ".$fields['date'].". We are running AMF ".$fields['version']."\n";
		
		//Check if Templater engine deed the same as we expected
		$this->assertEqual($result, $template);
	}
	function testTableTemplate() {
		//Make template string
		$template = "This is first line.\n";
		$template .= TEMPLATER_TABLE_LINE_START;
		$template .= "%line_number% - %line_text%\n";
		$template .= TEMPLATER_TABLE_LINE_END;
		$template .= "This is last line.";
		
		//Store template for test
		$testFileName = './tableTemplateTest.tmpl';
		file_put_contents($testFileName, $template);
		
		//Make list of replaces
		$array[0] = array('line_number' => '1', 'line_text' => 'первая строка, first line');
		$array[1] = array('line_number' => '2', 'line_text' => '');
		
		//Run template engine (template string is passed as parameter)	
		$result = Templater::makeTableFromTemplate($template, $array);
		
		//Build string for check
		$template = "This is first line.\n";
		$template .= $array[0]['line_number']." - ".$array[0]['line_text']."\n";
		$template .= $array[1]['line_number']." - ".$array[1]['line_text']."\n";
		$template .= "This is last line.";
		
		//Check if Templater engine deed the same as we expected
		$this->assertEqual($result, $template);
		
		//Run template engine (template is loaded from file)
		$result = Templater::makeTableFromTemplateFile($testFileName, $array);
		
		//Delete temporary template file
		unlink($testFileName);
		
		//Check if Templater engine deed the same as we expected
		$this->assertEqual($result, $template);		
		}
}

$test = new TestTemplates();
$test->switchToHTMLOutput();
$test->run();
echo $test;

?>