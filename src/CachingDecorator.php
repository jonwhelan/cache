<?php

namespace Whales\Cache;

use Whales\Cache\Maps\CacheMapInterface;

class CachingDecorator
{
	private $service;
	private $cacheMap;

	public function __construct(callable $service, CacheMapInterface $cacheMap)
	{
		$this->service = $service;
		$this->cacheMap = $cacheMap;
	}

	public function __invoke($key)
	{
		$iterator = $this->cacheMap->find($key);
		if ($iterator->valid()) {
			return $iterator->current();
		}

		$value = call_user_func($this->service, $key);
		$this->cacheMap->set($key, $value);
		return $value;
	}
}