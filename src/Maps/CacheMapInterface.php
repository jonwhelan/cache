<?php

namespace Whales\Cache\Maps;

interface CacheMapInterface
{
	/**
	 * @param $key
	 * @return mixed
	 * @throws \OutOfBoundsException
	 */
	public function get($key);

	/**
	 * @param $key
	 * @return \Iterator
	 */
	public function find($key);

	/**
	 * @param $key
	 * @param $value
	 * @return void
	 */
	public function set($key, $value);

	/**
	 * @param $key
	 * @return bool
	 */
	public function contains($key);

	/**
	 * @return void
	 */
	public function clear();

	/**
	 * @param $key
	 * @return void
	 */
	public function remove($key);
}
