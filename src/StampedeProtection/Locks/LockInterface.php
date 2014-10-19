<?php

namespace Whales\Cache\StampedeProtection\Locks;

interface LockInterface
{
	/**
	 * @return bool
	 */
	public function acquire();

	/**
	 * @return void
	 */
	public function release();
}