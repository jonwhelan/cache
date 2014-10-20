#Cache
##Examples
###Stampede Protection
####Object Instantiation
```php

	// Service in need of caching and stampede protection
	$callableService = function ($key) { return 'resourceIntensiveData'; };

	// Would need to be database-backed lock to actual work in the example
	// since the InMemoryLock has a per request lifecycle.
	$lock = new Cache\StampedeProtection\Locks\InMemoryLock;

	$memcached = new \Memcached();
	$memcached->addServer('127.0.0.1', 11211);

	$transientCache = new Cache\Maps\MemcachedCacheMap($memcached);

	// Acts as stale cache while service is processing data
	$persistentCache = new Cache\Maps\PdoCacheMap(
		new \PDO('mysql:host=localhost;dbname=test'),
		'cache'
	);

```
####Object Composition

```php

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

####Explanation

**Assumption**: The `$persistentCache` is primed so that there will always be some data returned even while the new data is being retrieved/regenerated.


* When a request comes into `$cachedService` it will attempt to find the item in the cache
    * The call to `find` will get passed on through:
        * `LockReleaseOnSettingCacheMap`, which delegates to:
        * `LinkedNodeCacheMap`
            * Here it will first check the `$transientCache` and either:
                * **Hit**, in which case it will return the cached value all the way back up to the original caller.
                * **Miss** and then:
                    * Check the `$persistentCache`, which is primed and *should* result in a hit, however since the `LockAffectedCacheMap` is
                      wrapping the `$persistentCache` it will report a cache miss since the lock is available (i.e. nobody else is currently working on regenerating the data for the cache)
                    * The cache miss is then propogated all the way back up to `CallableServiceCachingDecorator`.
    * Since the iterator returned from `find` is empty, the code then needs to call into the actual service to retrieve the fresh data.
        * The call to the service will pass through the `MutexLockAcquiringDecorator` object which will acquire the lock and let all future requests to the service
          know that someone is currently retrieving the data from the original datasource and regenerating the new cache data.
        * While the lock is held, all future requests will retrieve data from the stale `$persistentCache`. Remember that since this is wrapped
          in the `LockAffectedCacheMap` and the lock is now **unavailable** the `$persistentCache` will now report a cache hit and return it's stale data.
    * After the service is called and the fresh data is returned back up to `CallableServiceCachingDecorator`, we then set the cache to reflect the new data.
    * The call to `set` passes through `LockReleaseOnSettingCacheMap` which delegates the setting to `LinkedNodeCacheMap` which then will add the
      new data to both the `$transientCache` and `$persistentCache` (future stale cache).
    * After setting the new values, the lock is then released.
    * Finally the fresh data is passed out to the top-level caller.