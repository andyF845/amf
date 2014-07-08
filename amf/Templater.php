<?php
define ( TEMPLATER_TABLE_LINE_START, '<!--line_start-->' );
define ( TEMPLATER_TABLE_LINE_END, '<!--line_end-->' );
class Templater {
	/**
	 * Fils template string with data
	 *
	 * @param string $template
	 *        	string to be formated
	 * @param array $fields
	 *        	array("key"="value") to replace in template
	 * @return string Template string filled with data from $fields
	 */
	static public function makeText($template, $fields) {
		$keys = array_keys ( $fields );
		$values = array_values ( $fields );
		unset ( $fields );
		array_walk ( $keys, function (&$value) {
			$value = "%$value%";
		} );
		return str_replace ( $keys, $values, $template );
	}
	/**
	 * Inserts data into table template
	 * Template is loaded from file $fName
	 *
	 * @see makeTableFromTemplate();
	 */
	static public function makeTableFromTemplateFile($fname, $array, $returnThisIfEmpty = null) {
		// load template file
		if (! $template = file_get_contents ( $fname ))
			return false;
		
		return self::makeTableFromTemplate ( $template, $array, $returnThisIfEmpty );
	}
	/**
	 * Inserts data into table template
	 *
	 * @param string $template
	 *        	template string, must contain TEMPLATER_TABLE_LINE_START and TEMPLATER_TABLE_LINE_END strings to separate table header, line and footer.
	 * @param array $array
	 *        	array("line"=>array("key"=>"value")) to replace in template
	 * @param mixed $returnThisIfEmpty
	 *        	value to be returned if $array is empty.
	 * @return string Template string filled with data from $array
	 *        
	 */
	static public function makeTableFromTemplate($template, $array, $returnThisIfEmpty = null) {
		// get table_header, table_line and table_footer from template file
		$start = strpos ( $template, TEMPLATER_TABLE_LINE_START );
		$end = strpos ( $template, TEMPLATER_TABLE_LINE_END );
		
		$table_header	= substr (	$template, 
									0,
									$start );
		
		$table_line		= substr (	$template,
									$start + strlen ( TEMPLATER_TABLE_LINE_START ),
									$end - $start - strlen ( TEMPLATER_TABLE_LINE_START ) );
		
		$table_footer	= substr (	$template,
									$end + strlen ( TEMPLATER_TABLE_LINE_END ) );
		
		if ($array) {
			array_walk	(	$array, 
							function ($value) use(&$result, &$table_line) {
								$result .= Templater::makeText ( $table_line, $value );
							}
						);
		} else {
			return $returnThisIfEmpty;
		}
		return $table_header . $result . $table_footer;
	}
}
?>