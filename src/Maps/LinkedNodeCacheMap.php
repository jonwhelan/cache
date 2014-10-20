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
		$this->cacheMaps = new AggregateCacheMap(array_filter([
			$this->first,
			$this->next,
		]));
	}

	public function find($key)
	{

		$iterator = $this->first->find($key);
		if ($iterator->valid()) {
			return $iterator;
		}
		return (!is_null($this->next) && ($iterator = $this->next->find($key)) && $iterator->valid())
			? $iterator
			: new \EmptyIterator;
	}

	public function set($key, $value)
	{
		$this->cacheMaps->set($key, $value);
	}

	public function contains($key)
	{
		return $this->cacheMaps->contains($key);
	}

	public function clear()
	{
		$this->cacheMaps->clear();
	}

	public function remove($key)
	{
		$this->cacheMaps->remove($key);
	}
}