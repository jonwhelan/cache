<?php

use Whales\Cache\CallableServiceCachingDecorator;
use Whales\Cache\Maps\ArrayCacheMap;

class CallableServiceCachingDecoratorTest extends \PHPUnit_Framework_TestCase
{
	private $sut;
	private $cacheMap;

	protected function setUp()
	{
		$this->cacheMap = new ArrayCacheMap;
		$this->sut = new CallableServiceCachingDecorator(
			function ($key) { return 'serviceData'; },
			$this->cacheMap
		);
	}

	public function testReturnsDataStoredInCacheIfHit()
	{
		$this->cacheMap->set('key', 'test');
		$this->assertEquals('test', call_user_func($this->sut, 'key'));
	}

	public function testReturnsServiceDataAfterCacheMiss()
	{
		$value = call_user_func($this->sut, 'key');
		$this->assertEquals('serviceData', $value);
	}

	public function testAddsServiceDataToCacheAfterMiss()
	{
		call_user_func($this->sut, 'key');
		$this->assertEquals('serviceData', $this->cacheMap->get('key'));
	}
}