<?php
/**
 * CacheProviders test.
 * TestCache class is able to test any class, that implements CacheProvider interface.
 */
define ( AMF_BASE_PATH, '../amf/' );
include_once '../amf/core.php';
include_once '../amf/Mailer.php';
class TestMail extends TestCase {
	static $to;
	static $from;
	function testEmailUser() {
		$this->to = new EmailUser ( MAILER_USER_ROLE_TO, 'Имя Получателя', 'recipient@example.com' );
		$this->assertEqual ( $this->to, "To: =?utf-8?B?0JjQvNGPINCf0L7Qu9GD0YfQsNGC0LXQu9GP?=<recipient@example.com>" );
		$this->from = new EmailUser ( MAILER_USER_ROLE_FROM, 'Имя Отправителя', 'sender@example.com' );
		$this->assertEqual ( $this->from, "From: =?utf-8?B?0JjQvNGPINCe0YLQv9GA0LDQstC40YLQtdC70Y8=?=<sender@example.com>" );
	}
	function testMessage() {
		$msg = new Message ( 'Message subject', 'Message text', 'cp1251' );
		$msg->to = $this->to;
		$msg->from = $this->from;
		$msg->addFile ( './test/вложение.txt' );
		$msg->addFile ( './test/attach.txt' );
		$this->assertTrue ( Mailer::send ( $msg ) );
		$this->addResultLine('Everything looks fine from here, but also check your mail daemon logs.');
	}
}

$test = new TestMail ();
$test->switchToHTMLOutput();
$test->run ();
echo $test;
?>