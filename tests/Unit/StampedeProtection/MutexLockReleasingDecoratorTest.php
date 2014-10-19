<?php

use Whales\Cache\StampedeProtection\Locks\LockInterface;

class MutexLockReleasingDecoratorTest extends \PHPUnit_Framework_TestCase
{
	private $sut;
	private $lock;

	protected function setUp()
	{
		$service = function ($key) { return 'serviceData'; };
		$this->lock = $this->getMock(LockInterface::class);
		$this->sut = new \Whales\Cache\StampedeProtection\MutexLockReleasingDecorator($service, $this->lock);
	}

	public function testReleasesLock()
	{
		$this->lock->expects($this->once())->method('release');
		call_user_func($this->sut, 'key');
	}

	public function testReturnsServiceData()
	{
		$value = call_user_func($this->sut, 'key');
		$this->assertEquals('serviceData', $value);
	}
}