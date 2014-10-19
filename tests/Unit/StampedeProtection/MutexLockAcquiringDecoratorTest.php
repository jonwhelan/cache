<?php

use Whales\Cache\StampedeProtection\LockInterface;
use Whales\Cache\Maps\ArrayCacheMap;

class MutexLockAcquiringDecoratorTest extends \PHPUnit_Framework_TestCase
{
	private $sut;
	private $lock;

	protected function setUp()
	{
		$service = function ($key) { return 'serviceData'; };
		$this->lock = $this->getMock(LockInterface::class);
		$cacheMap = new ArrayCacheMap(['key' => 'cacheData']);
		$this->sut = new \Whales\Cache\StampedeProtection\MutexLockAcquiringDecorator($service, $this->lock, $cacheMap);
	}

	public function testReturnsServiceDataWhenLockSuccessfullyAcquired()
	{
		$this->lock->method('acquire')->willReturn(true);
		$value = call_user_func($this->sut, 'key');
		$this->assertEquals('serviceData', $value);
	}

	public function testReturnsStaleDataWhenCannotAcquireLock()
	{
		$this->lock->method('acquire')->willReturn(false);
		$value = call_user_func($this->sut, 'key');
		$this->assertEquals('cacheData', $value);
	}
}