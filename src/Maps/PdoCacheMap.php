<?php

namespace Whales\Cache\Maps;

use Whales\Cache\Maps\Support\MapGet;

class PdoCacheMap implements CacheMapInterface
{
	use MapGet;

	private $pdo;
	private $cacheTableName;

	public function __construct(\PDO $pdo, $cacheTableName)
	{
		$this->pdo = $pdo;
		$this->cacheTableName = $cacheTableName;
	}

	public function find($key)
	{
		$statement = $this->pdo->prepare("SELECT `value` FROM $this->cacheTableName WHERE `key` = :key");
		return ($statement->execute([':key' => $key]) && ($results = $statement->fetchAll()) && !empty($results))
			? new \ArrayIterator([current($results)['value']])
			: new \EmptyIterator;
	}

	public function set($key, $value)
	{
		$this->pdo->prepare("INSERT INTO $this->cacheTableName (key, value) VALUES (:key, :value)")->execute([
			':key' => $key,
			':value' => $value,
		]);
	}

	public function contains($key)
	{
		return $this->find($key)->valid();
	}

	public function clear()
	{
		$this->pdo->exec("DELETE FROM $this->cacheTableName");
	}

	public function remove($key)
	{
		$this->pdo->prepare("DELETE FROM $this->cacheTableName WHERE `key` = :key")->execute([
			':key' => $key
		]);
	}
}