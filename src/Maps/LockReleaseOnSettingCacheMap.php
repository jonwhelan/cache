<?php

namespace Whales\Cache\Maps;

use Whales\Cache\StampedeProtection\Locks\LockInterface;

class LockReleaseOnSettingCacheMap implements CacheMapInterface
{
	use CacheMapDecorator;

	private $cacheMap;
	private $lock;

	public function __construct(CacheMapInterface $cacheMap, LockInterface $lock)
	{
		$this->cacheMap = $cacheMap;
		$this->lock = $lock;
	}

	public function set($key, $value)
	{
		$this->cacheMap->set($key, $value);
		$this->lock->release();
	}
}
