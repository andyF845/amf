<?php
/**
 * Filecache implementation for CacheProvieder interface.
 */
class FileCacheProvider implements CacheProvider {
	public $active=true;
	public $storagePath = './cache/';
	function get($key) {
		if ($this->active) {
			if (!file_exists($this->storagePath)) {
				mkdir($this->storagePath);
			}
			return file_exists($this->storagePath.$key)? file_get_contents($this->storagePath.$key): false;
		} else {
			return false;
		}
	}
	function set($key,$data,$lifetime = 0) {
		if ($this->active)
			return file_put_contents($this->storagePath.$key,$data);
	}
	function delete($key) {
		if ($this->active)
			return file_exists($this->storagePath.$key)? unlink($this->storagePath.$key): false;
	}
	function deleteKeys($keysArray) {
		foreach ($keysArray as $key) {
			$this->delete($key);
		}
	}
}
?>