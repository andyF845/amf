<?php
require_once 'templater.php';

define ( ERR_PB_TEMPLATE_LOAD, 1100 );
class PageBuilderException extends Exception {
}
;
class PageBuilder {
	private $template;
	private $fields;
	/**
	 * PageBuilder object constructor
	 *
	 * @param string $fname
	 *        	template file name
	 * @throws Exception if template couldn't be load from file
	 */
	function __construct($fname) {
		if (! $this->template = file_get_contents ( $fname ))
			throw new PageBuilderException ( ERR_PB_TEMPLATE_LOAD );
	}
	public function buildPage() {
		return Templater::makeText ( $this->template, $this->fields );
	}
	/**
	 * Add field to replace in template.
	 * Any appearance of %$fieldName% in template, will be replaced with $fieldValue.
	 * This method only sets the data. Replace is done in builPage() method.
	 */
	public function setField($fieldName, $fieldValue) {
		$this->fields [$fieldName] = $fieldValue;
	}
	/**
	 * Set all the fields in one time by passing them as associated array.
	 * @see setField() also.
	 * @param array $fields
	 */
	public function setFields($fields) {
		$this->fields = $fields;
	}
}
?>