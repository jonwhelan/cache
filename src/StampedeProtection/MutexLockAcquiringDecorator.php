<?php

namespace Whales\Cache\StampedeProtection;

use Whales\Cache\Maps\CacheMapInterface;

class MutexLockAcquiringDecorator
{
	private $service;
	private $lock;
	private $persistentCache;

	public function __construct(callable $service, LockInterface $lock, CacheMapInterface $persistentCache)
	{
		$this->service = $service;
		$this->lock = $lock;
		$this->persistentCache = $persistentCache;
	}

	public function __invoke($key)
	{
		if ($this->lock->acquire()) {
			return call_user_func($this->service, $key);
		}
		return $this->persistentCache->get($key);
	}
}