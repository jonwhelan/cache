<?php

namespace Whales\Cache\Maps\Support;

trait MapGet
{
	public function get($key)
	{
		$iterator = $this->find($key);
		if (!$iterator->valid()) {
			throw new \OutOfBoundsException("Key ($key) does not exist. Use CacheMapInterface::find if client should not assume the key exists.");
		}
		return $iterator->current();
	}
}