<?php 
/**
 * CacheProviders test.
 * TestCache class is able to test any class, that implements CacheProvider interface.
 */
define (AMF_BASE_PATH, '../amf/');
include_once '../amf/core.php';

class TestCache extends TestCase {
	public $cache;
	/**
	 * Set cache data
	 */
	function testCacheSet() {
		$this->assertTrue($this->cache instanceof CacheProvider);
		$this->cache->set('key1','data for key1');
		$this->cache->set('key2','data for key2');
		$this->cache->set('key3',null);
	}
	/**
	 * Check if data was cached
	 */
	function testCacheGet() {
		$this->assertEqual($this->cache->get('key1'),'data for key1');
		$this->assertEqual($this->cache->get('key2'),'data for key2');
		$this->assertEqual($this->cache->get('key3'),null);
	}
	/**
	 * Delete cache data
	 */
	function testCacheDeleteKeys() {
		//Delete 1st and 3rd records
		$this->cache->deleteKeys(array('key1','key3'));
		//Check if only key1 and key3 records were deleted
		$this->assertFalse($this->cache->get('key1'));
		$this->assertFalse($this->cache->get('key3'));
		$this->assertEqual($this->cache->get('key2'),'data for key2');
	}
	/**
	 * Check if data was deleted
	 */
	function testCacheDeleteKey() {
		//Delete key2
		$this->cache->delete('key2');
		//Check if key2 was deleted
		$this->assertFalse($this->cache->get('key2'));
	}
}

//FileCache Test
$test = new TestCache();
$test->cache = new FileCacheProvider();
$test->cache->storagePath = './cache/';
$test->addResultLine(get_class($test->cache));
$test->switchToHTMLOutput();
$test->run();
echo $test;
unset($test);

//MemCache Test
$test = new TestCache();
$test->cache = new MemCacheProvider();
$test->addResultLine(get_class($test->cache));
$test->switchToHTMLOutput();
$test->run();
echo $test;

?>