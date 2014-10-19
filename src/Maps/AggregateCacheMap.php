<?php

namespace Whales\Cache\Maps;

use Whales\Cache\Maps\Support\MapGet;

class AggregateCacheMap implements CacheMapInterface
{
	use MapGet;

	private $cacheMaps = [];

	public function __construct(array $cacheMaps)
	{
		foreach ($cacheMaps as $cacheMap) {
			$this->addCacheMap($cacheMap);
		}
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

	private function addCacheMap(CacheMapInterface $cacheMap)
	{
		$this->cacheMaps[] = $cacheMap;
	}
}