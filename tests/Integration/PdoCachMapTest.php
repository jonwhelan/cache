<?php

class PdoCacheMapTest extends \PHPUnit_Framework_TestCase
{
	private $sut;
	private $pdo;

	protected function setUp()
	{
		$this->pdo = new \PDO('sqlite::memory:');
		$this->pdo->exec("CREATE TABLE cache (key int, value text)");
		$this->sut = new \Whales\Cache\Maps\PdoCacheMap($this->pdo, 'cache');
	}

	public function testFindReturnsEmptyIteratorOnCacheMiss()
	{
		$iterator = $this->sut->find('key');
		$this->assertFalse($iterator->valid());
	}

	public function testRecordGetsAddedToCacheAndCanBeRetrieved()
	{
		$this->sut->set('key', 'value');
		$this->assertEquals('value', $this->sut->get('key'));
		$this->pdo->exec("DELETE FROM cache");
	}

	public function testGetThrowsExceptionOnCacheMiss()
	{
		try {
			$this->sut->get('nonExistentKey');
			$this->fail();
		} catch (\Exception $e) {
			$this->assertInstanceOf(\OutOfBoundsException::class, $e);
		}
	}

	public function testContains()
	{
		$this->assertFalse($this->sut->contains('nonExistentKey'));
		$this->sut->set('key', 'value');
		$this->assertTrue($this->sut->contains('key'));
		$this->pdo->exec("DELETE FROM cache");
	}

	public function testKeyCanBeDeletedFromCache()
	{
		$this->sut->set('key', 'value');
		$this->assertTrue($this->sut->contains('key'));
		$this->sut->remove('key');
		$this->assertFalse($this->sut->contains('key'));
	}

	public function testCacheCanBeCleared()
	{
		$this->sut->set('key1', 'value');
		$this->sut->set('key2', 'value');
		$this->assertTrue($this->sut->contains('key1'));
		$this->assertTrue($this->sut->contains('key2'));
		$this->sut->clear();
		$this->assertFalse($this->sut->contains('key1'));
		$this->assertFalse($this->sut->contains('key2'));
	}

	protected function tearDown()
	{
		$this->pdo->exec("DROP TABLE cache");
	}
}