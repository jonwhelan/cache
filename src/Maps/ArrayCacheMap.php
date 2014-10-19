<?php

namespace Whales\Cache\Maps;

use Whales\Cache\Maps\Support\MapGet;

class ArrayCacheMap implements CacheMapInterface
{
	use MapGet;

	private $cache;

	public function __construct(array $cache = [])
	{
		$this->cache = $cache;
	}

	public function find($key)
	{
		return array_key_exists($key, $this->cache)
			? new \ArrayIterator([$this->cache[$key]])
			: new \EmptyIterator;
	}

	public function set($key, $value)
	{
		$this->cache[$key] = $value;
	}

	public function contains($key)
	{
		return array_key_exists($key, $this->cache);
	}

	public function clear()
	{
		$this->cache = [];
	}

	public function remove($key)
	{
		unset($this->cache[$key]);
	}
}