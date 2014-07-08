<?php
/**
 * HTTP Headers
 */
class HeaderWriter {
	/**
	 * Sends $headerString to client
	 * @param string $headerString
	 * @return true on success, false if headers already have been sent before.
	 */
	static function sendHeaders($headerString) {
		if (headers_sent())
			return false;
		header ( $headerString );
		return true;
	}
	static function headerJSON() {
		return self::sendHeaders( "Content-type: application/json; charset=utf-8" );
	}
	static function headerHTML() {
		return self::sendHeaders( "Content-Type: text/html; charset=utf-8" );
	}
	static function headerXML() {
		return self::sendHeaders( "Content-type: application/xml; charset=utf-8" );
	}
	static function headerRedirect($url) {
		return self::sendHeaders( "Location: $url" );
	}
}
?>