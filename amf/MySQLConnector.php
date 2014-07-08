<?php
class MySQLConnector {
	private $mysqli;
	public $errorInfo;
	
	/**
	 * Establish connection to mySQL server
	 *
	 * @param string $server
	 *        	mySQL server name
	 * @param string $username
	 *        	mySQL user name
	 * @param string $password
	 *        	mySQL user password
	 * @param string $databse
	 *        	mySQL data base name
	 * @return bool Returns true on success or false on failure.
	 *         Additional error info is stored in errorInfo fileld;
	 *        
	 */
	function __construct($server, $username, $password, $databse) {
		$this->mysqli = new mysqli ( $server, $username, $password, $databse );
		$this->errorInfo = $this->mysqli->connect_error;
		if ($this->mysqli->connect_errno) {
			return false;
		}
		$this->mysqli->query ( "SET CHARACTER SET utf8" );
		return true;
	}
	function __destruct() {
		$this->mysqli->close ();
	}
	
	/**
	 * Returns sql-safe string for given string
	 */
	function escapeString($str) {
		return $this->mysqli->real_escape_string ( $str );
	}
	
	/**
	 * Performs a query on the database
	 *
	 * @param string $sql
	 *        	query string
	 * @return false on failure.
	 *         For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries mysqli_query will return a mysqli_result object.
	 *         For other successful queries mysqli_query will return true.
	 */
	function goSQL($sql) {
		$res = $this->mysqli->query ( $sql );
		$err = $this->mysqli->connect_error;
		$this->errorInfo = $this->mysqli->error;
		if (! $err)
			return $res;
		else
			return false;
	}
	
	/**
	 * Performs a query on the database and returns result as associated array.
	 * <b>To perform non-select queries, please, use goSQL() instead.</b>
	 *
	 * @param string $sql
	 *        	query string
	 * @return false on failure.
	 *         For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries will return an Array().
	 *         If the query returned empty data set, false will be returned.
	 */
	function getArrayResult($sql) {
		if ((! $res = $this->goSQL ( $sql )) || ($res->num_rows == 0))
			return false;
		while ( $item = $res->fetch_assoc () ) {
			$items [] = $item;
		}
		return $items;
	}
	
	/**
	 * Performs a query on the database and returns result as XML data string.
	 * <b>To perform non-select queries, please, use goSQL() instead.</b>
	 *
	 * @param string $sql
	 *        	query string
	 * @return false on failure.
	 *         For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries will return an XML data string.
	 *         If the query returned empty data set, false will be returned.
	 */
	function getXMLResult($sql) {
		if ((! $res = $this->goSQL ( $sql )) || ($res->num_rows == 0))
			return false;
		$xml = new XMLWriter ();
		$xml->openMemory ();
		$xml->startDocument ( "1.0", "UTF-8" );
		$xml->startElement ( "items" );
		while ( $item = $res->fetch_assoc () ) {
			$xml->startElement ( "item" );
			foreach ( $item as $key => $value ) {
				$xml->writeElement ( $key, $value );
			}
			$xml->endElement ();
		}
		$xml->endElement ();
		return $xml->outputMemory ();
	}
	
	/**
	 * Performs a query on the database and returns result as JSON string.
	 * <b>To perform non-select queries, please, use goSQL() instead.</b>
	 *
	 * @param string $sql
	 *        	query string
	 * @param mixed $defaultReturnValue
	 *        	This value will be returned if function fails or query returns empty data set.
	 *        	Default value is false.
	 * @param bool $returnAsArray
	 *        	if true, result will represent json array even if there will be 0 or 1 records.
	 * @return $defaultReturnValue on failure.
	 *         For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries will return an Array().
	 *         If the query returned empty data set, defaultReturnValue will be returned.
	 */
	function getJSONResult($sql, $defaultReturnValue = false, $returnAsArray = false) {
		if (! $res = $this->goSQL ( $sql ))
			return $defaultReturnValue;
		switch ($res->num_rows) {
			case 0 : // This is not the magic numbers you are looking for. 0 records is 0 records. That's all, folks.
				return $defaultReturnValue;
			case 1 : // Also not so magic. One record is more than 0, but still not many.
				$res = $res->fetch_assoc ();
				if ($returnAsArray)
					$res = array (
							$res 
					);
				break;
			default :
				while ( $item = $res->fetch_assoc () ) {
					$items [] = $item;
				}
				$res = $items;
		}
		return json_encode ( $res );
	}
}
?>