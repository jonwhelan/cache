<?php

namespace Whales\Cache\Maps;

class KeyStrategyCacheMap implements CacheMapInterface
{
	use CacheMapDecorator;

	private $keyStrategy;

	public function __construct(CacheMapInterface $cacheMap, callable $keyStrategy)
	{
		$this->cacheMap = $cacheMap;
		$this->keyStrategy = $keyStrategy;
	}

	public function find($key)
	{
		return $this->cacheMap->find(call_user_func($this->keyStrategy, $key));
	}

	public function set($key, $value)
	{
		$this->cacheMap->set(call_user_func($this->keyStrategy, $key), $value);
	}

	public function contains($key)
	{
		return $this->cacheMap->contains(call_user_func($this->keyStrategy, $key));
	}

	public function remove($key)
	{
		$this->cacheMap->remove(call_user_func($this->keyStrategy, $key));
	}
}