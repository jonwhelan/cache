#Cache
##Examples
###Stampede Protection
```php

	use Whales\Cache;
	use Whales\Cache\StampedeProtection\MutexLockAcquiringDecorator;
	use Whales\Cache\Maps\LockReleaseOnSettingCacheMap;
	use Whales\Cache\Maps\LinkedNodeCacheMap;
	use Whales\Cache\Maps\LockAffectedCacheMap;
	use Whales\Cache\CallableServiceCachingDecorator;

	// Service in need of stampede protection
	$callableService = function ($key) { return 'resourceIntensiveData'; };

	// Would need to be database-backed lock to actual work in example
	$lock = new Cache\StampedeProtection\Locks\InMemoryLock;

	$memcached = new \Memcached();
	$memcached->addServer('127.0.0.1', 11211);

	$transientCache = new Cache\Maps\MemcachedCacheMap($memcached);

	// Acts as stale cache while service is processing data
	$persistentCache = new Cache\Maps\PdoCacheMap(
		new \PDO('mysql:host=localhost;dbname=test'),
		'cache'
	);

	$cachedService = new CallableServiceCachingDecorator(
		new MutexLockAcquiringDecorator(
			$callableService,
			$lock,
			$persistentCache
		),
		new LockReleaseOnSettingCacheMap(
			new LinkedNodeCacheMap(
				$transientCache,
				new LockAffectedCacheMap(
					$persistentCache,
					$lock
				)
			),
			$lock
		)
	);

	$data = call_user_func($cachedService, 'key1234');

```

