<?php

use Whales\Cache\Maps\ArrayCacheMap;

class PdoPersistentCacheDecoratorTest extends \PHPUnit_Framework_TestCase
{
	private $pdo;
	private $sut;
	private $temporaryCache;

	public function setUp()
	{
		$this->pdo = new \PDO('sqlite::memory:');
		$this->pdo->exec('CREATE TABLE test (`key` int, `value` text)');
		$this->temporaryCache = new ArrayCacheMap;
		$this->sut = new \Whales\Cache\StampedeProtection\PdoPersistentCacheDecorator($this->temporaryCache, $this->pdo, 'test');
	}

	public function testStoresDataInPersistentCache()
	{
		$this->sut->set('key', 'value');
		$result = $this->pdo->query('SELECT * FROM test')->fetchAll();
		$this->assertCount(1, $result);
		$this->assertEquals('value', $result[0]['value']);
	}

	public function testStoresDataInTemporaryCache()
	{
		$this->sut->set('key', 'value');
		$this->assertCount(1, $this->temporaryCache->find('key'));
		$this->assertEquals('value', $this->temporaryCache->get('key'));
	}
}