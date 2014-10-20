<?php

namespace Whales\Cache\Maps;

trait CacheMapDecorator
{
	/**
	 * @var CacheMapInterface
	 */
	private $cacheMap;

	public function get($key)
	{
		return $this->cacheMap->get($key);
	}

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
