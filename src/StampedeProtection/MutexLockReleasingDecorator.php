<?php

namespace Whales\Cache\StampedeProtection;

use Whales\Cache\StampedeProtection\Locks\LockInterface;

class MutexLockReleasingDecorator
{
	private $service;
	private $lock;

	public function __construct(callable $service, LockInterface $lock)
	{
		$this->service = $service;
		$this->lock = $lock;
	}

	public function __invoke($key)
	{
		$value = call_user_func($this->service, $key);
		$this->lock->release();
		return $value;
	}
}