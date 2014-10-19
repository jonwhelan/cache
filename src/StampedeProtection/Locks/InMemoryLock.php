<?php

namespace Whales\Cache\StampedeProtection\Locks;

class InMemoryLock implements LockInterface
{
	private $isLocked = false;

	public function acquire()
	{
		if (!$this->isLocked) {
			$this->isLocked = true;
		}
		return $this->isLocked;
	}

	public function release()
	{
		$this->isLocked = false;
	}
}