<?php

use Whales\Cache\Maps\ArrayCacheMap;

class AggregateCacheMapTest extends \PHPUnit_Framework_TestCase
{
	private $sut;
	private $cache1;
	private $cache2;

	public function setUp()
	{
		$this->cache1 = new ArrayCacheMap;
		$this->cache2 = new ArrayCacheMap;
		$this->sut = new \Whales\Cache\Maps\AggregateCacheMap([$this->cache1, $this->cache2]);
	}

	public function testStoresDataInAllCaches()
	{
		$this->sut->set('key', 'value');
		$this->assertEquals('value', $this->cache1->get('key'));
		$this->assertEquals('value', $this->cache2->get('key'));
	}

	public function testFindReturnsEmptyIteratorOnMiss()
	{
		$this->assertFalse($this->sut->find('nonExistentKey')->valid());
	}

	public function testFindReturnsValueStoredInChildCache()
	{
		$this->cache1->set('key', 'value');
		$iterator = $this->sut->find('key');
		$this->assertTrue($iterator->valid());
		$this->assertEquals('value', $iterator->current());
	}

	public function testContainsReturnsTrueWhenAtLeastOneChildCacheHits()
	{
		$this->cache1->set('key1', 'value');
		$this->cache2->set('key2', 'value');
		$this->assertTrue($this->sut->contains('key1'));
		$this->assertTrue($this->sut->contains('key2'));
	}

	public function testRemovesFromAllCaches()
	{
		$this->cache1->set('key', 'value');
		$this->cache2->set('key', 'value');
		$this->sut->remove('key');
		$this->assertFalse($this->cache1->contains('key'));
		$this->assertFalse($this->cache2->contains('key'));
	}

	public function testClearsAllCaches()
	{
		$this->cache1->set('key', 'value');
		$this->cache2->set('key', 'value');
		$this->sut->clear();
		$this->assertFalse($this->cache1->contains('key'));
		$this->assertFalse($this->cache2->contains('key'));

	}
}