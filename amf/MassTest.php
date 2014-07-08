<?php
/**
 * Mass test helper
 */
class MassTest{
	/**
	 * Runs all the tests found at $path
	 */
	static function runAll($path) {
		$filename = pathinfo ( $_SERVER [SCRIPT_FILENAME], PATHINFO_BASENAME );
		$tests = scandir ( $path );
		unset ( $tests [$filename] );
		foreach ( $tests as $test )
		if (is_file ( $test ) && ($test != $filename)) {
			echo "<h2>$test</h2>";
			include_once $test;
		}
		}
}
?>