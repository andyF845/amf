<?php
/**
 * Memecahce implementation for CacheProvider interface.
 */
class MemCacheProvider implements CacheProvider {
	public $active=true;
	private $cache;
	function __construct($server = '127.0.0.1' ,$port = 11211) {
		$this->cache = new MemCache;
		if ($this->cache->connect($server,$port)) {
		} else {
			throw new Exception (ERROR_MEMCACHE_CONNECTION_FAILED);
		}
	}
	function get($key) {
		if ($this->active)
			return $this->cache->get($key);
	}
	function set($key,$data,$lifetime = 0) {
		if ($this->active)
			return $this->cache->set($key,$data,false,$lifetime);
	}
	function delete($key) {
		if ($this->active)
			return ($this->cache->delete($key));
	}
	function deleteKeys($keysArray) {
		foreach ($keysArray as $key) {
			$this->delete($key);
		}
	}
	function __destruct() {
		$this->cache->close();
	}	
}
?>