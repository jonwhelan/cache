<?php

namespace Whales\Cache\Maps;

use Whales\Cache\Maps\Support\MapGet;

class ArrayCacheMap implements CacheMapInterface
{
	use MapGet;

	private $cachedData;

	public function __construct(array $cachedData = [])
	{
		$this->cachedData = $cachedData;
	}

	public function find($key)
	{
		return array_key_exists($key, $this->cachedData)
			? new \ArrayIterator([$this->cachedData[$key]])
			: new \EmptyIterator;
	}

	public function set($key, $value)
	{
		$this->cachedData[$key] = $value;
	}

	public function contains($key)
	{
		return array_key_exists($key, $this->cachedData);
	}

	public function clear()
	{
		$this->cachedData = [];
	}

	public function remove($key)
	{
		unset($this->cachedData[$key]);
	}
}