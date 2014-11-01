<?php

namespace Whales\Cache\Maps;

use Whales\Cache\Maps\Support\MapGet;

trait CacheMapDecorator
{
	use MapGet;

	/**
	 * @var CacheMapInterface
	 */
	private $cacheMap;

	public function find($key)
	{
		return $this->cacheMap->find($key);
	}

	public function set($key, $item)
	{
		$this->cacheMap->set($key, $item);
	}

	public function contains($key)
	{
		return $this->cacheMap->contains($key);
	}

	public function clear()
	{
		$this->cacheMap->clear();
	}

	public function remove($key)
	{
		$this->cacheMap->remove($key);
	}
}
