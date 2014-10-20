<?php

namespace Whales\Cache\StampedeProtection\Locks;

class InMemoryLock implements LockInterface
{
	private $isLocked = false;

	public function acquire()
	{
		if ($this->isLocked) {
			return false;
		}
		return $this->isLocked = true;
	}

	public function release()
	{
		$this->isLocked = false;
	}

	public function isAvailable()
	{
		return !$this->isLocked;
	}
}