<?php

namespace Whales\Cache;

use Whales\Cache\Maps\CacheMapInterface;

trait CacheMapDecorator
{
	/**
	 * @var CacheMapInterface
	 */
	private $cache;

	public function get($key)
	{
		return $this->cache->get($key);
	}

	public function find($key)
	{
		return $this->cache->find($key);
	}

	public function set($key, $item)
	{
		$this->cache->set($key, $item);
	}

	public function contains($key)
	{
		return $this->cache->contains($key);
	}

	public function clear()
	{
		$this->cache->clear();
	}

	public function remove($key)
	{
		$this->cache->remove($key);
	}
}
