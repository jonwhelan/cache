<?php

namespace Whales\Cache\StampedeProtection;

use Whales\Cache\CacheMapDecorator;
use Whales\Cache\Maps\CacheMapInterface;

class PdoPersistentCacheDecorator implements CacheMapInterface
{
	use CacheMapDecorator;

	private $pdo;
	private $tableName;

	public function __construct(CacheMapInterface $cache, \PDO $pdo, $tableName)
	{
		$this->cache = $cache;
		$this->pdo = $pdo;
		$this->tableName = $tableName;
	}

	public function set($key, $value)
	{
		$this->pdo->prepare("INSERT INTO {$this->tableName} (key, value) VALUES (:key, :value)")->execute([
			':key' => $key,
			':value' => $value,
		]);

		$this->cache->set($key, $value);
	}
}