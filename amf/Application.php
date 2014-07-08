<?php
/**
 * Base class for http application
 */
class Application { 
	/**
	 * Validates $string value for variable $name  
	 * @param string $name
	 * @param string $value
	 * @return valid string value for $name variable 
	 */
	protected function validateString($name,$value) {
		return $value;
	}
	/**
	 * Fills object fields with $params data.
	 * Field names should be prefixed by:
	 * "str_" for string values
	 * "int_" for integer values
	 * "raw_" for other values
	 * Data for string values is validated with validateString()
	 * Data for integer values is casted to integer
	 * Data for raw values is passed as it is.
	 * @param array $params
	 */
	private final function initParams(array $params) {
		$vars = get_class_vars ( get_class($this) );
		foreach ( $vars as $var => $default ) {
			if (list ( $type, $name ) = explode ( "_", $var ))
				switch ($type) {
					case 'raw' :
						$this->$var = isSet ( $params [$name] ) ? $params [$name] : $default;
						break;
					case 'str' :
						$this->$var = isSet ( $params [$name] ) ? $this->validateString($name, $params [$name]) : $default;
						break;
					case 'int' :
						$this->$var = isSet ( $params [$name] ) ? ( int ) $params [$name] : $default;
						break;
				}
		}
	}
	/**
	 * @param array $params ("key"=>"value")
	 */
	public function __construct($params) {
		$this::initParams($params);
	}
}
?>