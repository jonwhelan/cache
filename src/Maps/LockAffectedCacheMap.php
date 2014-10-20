<?php

namespace Whales\Cache\Maps;

use Whales\Cache\StampedeProtection\Locks\LockInterface;

class LockAffectedCacheMap implements CacheMapInterface
{
	use CacheMapDecorator;

	private $cacheMap;
	private $lock;

	public function __construct(CacheMapInterface $cacheMap, LockInterface $lock)
	{
		$this->cacheMap = $cacheMap;
		$this->lock = $lock;
	}

	public function find($key)
	{
		return $this->lock->isAvailable() ? new \EmptyIterator : $this->cacheMap->find($key);
	}
}