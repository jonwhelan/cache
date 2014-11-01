<?php

namespace Whales\Cache;

use Whales\Cache\Maps\CacheMapInterface;

class CallableServiceCachingDecorator
{
	private $service;
	private $cacheMap;

	public function __construct(callable $service, CacheMapInterface $cacheMap)
	{
		$this->service = $service;
		$this->cacheMap = $cacheMap;
	}

	public function __invoke($variadic)
	{
		$args = func_get_args();
		$iterator = $this->cacheMap->find($args);
		if ($iterator->valid()) {
			return $iterator->current();
		}

		$value = call_user_func_array($this->service, $args);
		$this->cacheMap->set($args, $value);
		return $value;
	}
}