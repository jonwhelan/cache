<?php

namespace Whales\Cache\Maps;

use Whales\Cache\Maps\Support\MapGet;

class LinkedNodeCacheMap implements CacheMapInterface
{
	use MapGet;

	private $first;
	private $next;
	private $cacheMaps;

	public function __construct(CacheMapInterface $first, CacheMapInterface $next = null)
	{
		$this->first = $first;
		$this->next = $next;
		$this->cacheMaps = array_filter([
			$this->first,
			$this->next,
		]);
	}

	public function find($key)
	{
		foreach ($this->cacheMaps as $cacheMap) {
			$iterator = $cacheMap->find($key);
			if ($iterator->valid()) return $iterator;
		}
		return new \EmptyIterator;
	}

	public function set($key, $value)
	{
		foreach ($this->cacheMaps as $cacheMap) {
			$cacheMap->set($key, $value);
		}
	}

	public function contains($key)
	{
		foreach ($this->cacheMaps as $cacheMap) {
			if ($cacheMap->contains($key)) return true;
		}
		return false;
	}

	public function clear()
	{
		foreach ($this->cacheMaps as $cacheMap) {
			$cacheMap->clear();
		}
	}

	public function remove($key)
	{
		foreach ($this->cacheMaps as $cacheMap) {
			$cacheMap->remove($key);
		}
	}
}