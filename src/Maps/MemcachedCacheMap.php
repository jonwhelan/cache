<?php

namespace Whales\Cache\Maps;

use Whales\Cache\Maps\Support\MapGet;

class MemcachedCacheMap implements CacheMapInterface
{
	use MapGet;

	private $memcached;
	private $ttl;

	public function __construct(\Memcached $memcached, $ttl = 600)
	{
		$this->memcached = $memcached;
		$this->ttl = $ttl;
	}

	public function find($key)
	{
		$cacheResult = $this->memcached->get($key);
		return ($this->memcached->getResultCode() === \Memcached::RES_SUCCESS)
			? new \ArrayIterator([$cacheResult])
			: new \EmptyIterator;
	}

	public function set($key, $value)
	{
		$this->memcached->set($key, $value, time() + $this->ttl);
	}

	public function contains($key)
	{
		return $this->find($key)->valid();
	}

	public function clear()
	{
		$this->memcached->flush();
	}

	public function remove($key)
	{
		$this->memcached->delete($key);
	}
}