<?php
/**
 * CacheProvider Interface
  */
interface CacheProvider {
	public function get($key);
	public function set($key,$data,$lifetime = 0);
	public function delete($key);
	public function deleteKeys($keysArray);
}
?>