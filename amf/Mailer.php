<?php
define(MAILER_DEFAULT_FS_ENCODING,		'cp1251');
define(MAILER_USER_ROLE_TO,		'To');
define(MAILER_USER_ROLE_FROM,	'From');
define(ERR_MAILER_BAD_MESSAGE,			1050);
define(ERR_MAILER_SEND_FAIL,			ERR_MAILER_BAD_MESSAGE+1);
define(ERR_MAILER_RECIPIENT_NOT_SET,	ERR_MAILER_BAD_MESSAGE+2);
define(ERR_MAILER_SENDER_NOT_SET,		ERR_MAILER_BAD_MESSAGE+3);
define(ERR_MAILER_FILENAME_EMPTY,		ERR_MAILER_BAD_MESSAGE+4);
define(ERR_MAILER_FILE_NOT_FOUND,		ERR_MAILER_BAD_MESSAGE+5);

class EmailException extends Exception {};
class EmailSendException extends EmailException {};
class EmailBadMessage extends EmailException {};
class EmailAddressNotSet extends EmailException {};

/**
 * Provides sending e-mail message sending ability
 */
final class Mailer {
	/**
	 * 
	 * @param Message $message instance of Message class
	 * @return true if message was sent, throws error otherwise
	 * @throws
	 * EmailBadMessage if $message is not an instance of Message class.
	 * EmailSendException if message sending fails.  
	 */
	static function Send(Message $message) {
		if (! $message instanceof Message)
			throw new EmailBadMessage(ERR_MAILER_BAD_MESSAGE);
		if (! mail ( $message->To->email, $message->subject, $message->getBody(), $message->getHeaders() ))
			throw new EmailSendException(ERR_MAILER_SEND_FAIL);
		//Logger::log("Message sent to ".$message->To->email,LOG_INFO);//TODO не показывает кому отправили
		return true;
	}
}

function getBase64String($string) {
	return "=?utf-8?B?".base64_encode($string)."?=";
}
function getFieldString($fieldName,$fieldValue) {
	return "$fieldName: $fieldValue;".EOL;
}

/**
 * E-mail user object 
 */
final class EmailUser {
	public $name;
	public $email;
	public $role;
	private function getName() {
		return getBase64String($this->name);
	} 
	/**
	 * 
	 * @param string $role EMAIL_USER_ROLE_TO or EMAIL_USER_ROLE_FROM
	 * @param string $name Display user name
	 * @param string $email E-mail address
	 */
	function __construct($role,$name,$email) {
		$this->role  = $role;
		$this->name  = $name;
		$this->email = $email;
	}
	function __toString() {
		return sprintf("%s: %s<%s>",$this->role,$this->getName(),$this->email);
	}
}

/**
 * E-mail message object
 * @see Mailer class
 */
final class Message {
	public $to;
	public $from;
	public $reply;
	public $subject;
	public $text;
	private $files;
	private $boudary;
	private $partDivider;
	private $fsNativeEncoding;
	
	/**
	 * Creates new message object
	 * @param string $subject
	 * @param string $text
	 * @param string $fsNativeEncoding Filesystem native encoding. cp1251 on Windows system
	 */
	public function __construct($subject = null, $text = null, $fsNativeEncoding = MAILER_DEFAULT_FS_ENCODING) {
		$this->boundary    		= md5(uniqid(time()));
		$this->partDivider 		= EOL . "--" . $this->boundary . EOL;
		$this->text        		= $text;
		$this->subject     		= $subject;
		$this->fsNativeEncoding = $fsNativeEncoding;
	}
	/**
	 * Returns e-mail message headers
	 * @throws EmailAddressNotSet if e-mail address of sender or recipient is not set.
	 */
	public function getHeaders() {
		
		if (! $this->to instanceof EmailUser) throw new EmailAddressNotSet(ERR_MAILER_RECIPIENT_NOT_SET);
		if (! $this->from instanceof EmailUser) throw new EmailAddressNotSet(ERR_MAILER_SENDER_NOT_SET);
		
		$header  = getFieldString ( "MIME-Version", "1.0" );
		$header .= getFieldString ( "Content-Type", "multipart/mixed; boundary=\"" . $this->boundary . "\"" );
		$header .= (string) $this->to.EOL;
		$header .= (string) $this->from.EOL;
		$header .= ($this->reply)? (string) $this->reply : "";
		return $header;
	}
	/**
	 * Returns e-mail message body
	 */
	public function getBody() {
		$text  = $this->partDivider;
		$text .= getFieldString ( "Content-Type", "text/html; charset=utf-8" );
		$text .= getFieldString ( "Content-Transfer-Encoding", "base64" );
		$text .= EOL;
		$text .= chunk_split ( base64_encode ( $this->text ) );
		if ($this->files)
			foreach($this->files as $file)
				$text .= $this->realAddFile($file);
		$text.= "--".$this->boundary."--".EOL;
		return $text;
	}
	/**
	 * Add file to message
	 * @param string $filename - file name to be attached to e-mail message. 
	 * If $filename contains some native characters, be sure to use proper encoding (@see __construct(..., $fsNativeEncoding) )
	 */
	public function addFile($filename) {
		$this->files [] = $filename;
	}
	/**
	 * Returns base64 encoded file contents to include in e-mail message body.
	 * @throws EmailException if file not found or file name is empty
	 * @return string multipart of message body
	 */	
	private function realAddFile($fname) {
		$fname_fsNative = $this->fsNativeEncoding? iconv('UTF-8', $this->fsNativeEncoding, $fname): $fname;
		if (!$fname_fsNative) throw new EmailException(ERR_MAILER_FILENAME_EMPTY);
		if (!file_exists($fname_fsNative)) throw new  EmailException(ERR_MAILER_FILE_NOT_FOUND);
		$file = file_get_contents ( $fname_fsNative );
		if ($file) {
			$filename   = getBase64String( basename ( $fname ) );
			$multipart .= $this->partDivider;
			$multipart .= getFieldString ( "Content-Type", "application/octet-stream; name=\"$filename\"" );
			$multipart .= getFieldString ( "Content-Transfer-Encoding", "base64" );
			$multipart .= getFieldString ( "Content-Disposition", "attachment; filename=\"$filename\"" );
			$multipart .= getFieldString ( "Content-Length", filesize($fname_fsNative) );
			$multipart .= EOL;
			$multipart .= chunk_split ( base64_encode ( $file ) );
			return $multipart;
		} else
			return false;
	}
}
?>
 