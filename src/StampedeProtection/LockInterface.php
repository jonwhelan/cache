<?php

namespace Whales\Cache\StampedeProtection;

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