<?php

declare(strict_types=1);

namespace LaraArabDev\TurboTags;

use Closure;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

/**
 * Manages cache operations for TurboTags.
 *
 * Provides a centralized layer for caching tag lookups, suggestions,
 * and collections. All cache keys are tracked for efficient flushing.
 */
class TagCache
{
    /**
     * Whether cache flushing is currently deferred.
     */
    protected static bool $deferFlush = false;

    /**
     * Execute a callback with cache flushing deferred.
     *
     * Batches all flush calls during the callback into a single
     * flush after it completes. Prevents N flushes during batch creates.
     *
     * @param  Closure  $callback  The callback to execute.
     */
    public static function withoutFlushing(Closure $callback): mixed
    {
        static::$deferFlush = true;

        try {
            return $callback();
        } finally {
            static::$deferFlush = false;
            static::flush();
        }
    }

    /**
     * Determine if tag caching is enabled.
     */
    public static function enabled(): bool
    {
        return (bool) config('laravel-turbo-tags.cache.enabled', false);
    }

    /**
     * Get the cache store instance.
     */
    public static function store(): Repository
    {
        $store = config('laravel-turbo-tags.cache.store');

        return Cache::store(is_string($store) ? $store : null);
    }

    /**
     * Get the cache TTL in seconds.
     */
    public static function ttl(): int
    {
        $ttl = config('laravel-turbo-tags.cache.ttl', 3600);

        return is_int($ttl) ? $ttl : 3600;
    }

    /**
     * Build a fully qualified cache key.
     *
     * @param  string  $suffix  The key suffix to append after the prefix.
     */
    public static function key(string $suffix): string
    {
        return static::prefix().':'.$suffix;
    }

    /**
     * Get a value from cache, or execute the callback and store the result.
     *
     * When caching is disabled, the callback is executed directly.
     *
     * @param  string  $suffix  The cache key suffix.
     * @param  Closure  $callback  The callback to execute on cache miss.
     */
    public static function remember(string $suffix, Closure $callback): mixed
    {
        if (! static::enabled()) {
            return $callback();
        }

        $key = static::key($suffix);
        $store = static::store();

        if ($store->has($key)) {
            return $store->get($key);
        }

        $value = $callback();

        static::track($suffix);
        $store->put($key, $value, static::ttl());

        return $value;
    }

    /**
     * Store a value in cache directly.
     *
     * Only operates when caching is enabled.
     *
     * @param  string  $suffix  The cache key suffix.
     * @param  mixed  $value  The value to cache.
     */
    public static function put(string $suffix, mixed $value): void
    {
        if (! static::enabled()) {
            return;
        }

        static::track($suffix);
        static::store()->put(static::key($suffix), $value, static::ttl());
    }

    /**
     * Retrieve a value from cache.
     *
     * Returns null when caching is disabled or key is missing.
     *
     * @param  string  $suffix  The cache key suffix.
     */
    public static function get(string $suffix): mixed
    {
        if (! static::enabled()) {
            return null;
        }

        return static::store()->get(static::key($suffix));
    }

    /**
     * Check if a cache key exists.
     *
     * @param  string  $suffix  The cache key suffix.
     */
    public static function has(string $suffix): bool
    {
        if (! static::enabled()) {
            return false;
        }

        return static::store()->has(static::key($suffix));
    }

    /**
     * Remove a specific cache entry.
     *
     * @param  string  $suffix  The cache key suffix.
     */
    public static function forget(string $suffix): void
    {
        if (! static::enabled()) {
            return;
        }

        static::store()->forget(static::key($suffix));
    }

    /**
     * Flush all tracked cache entries.
     *
     * Iterates through all tracked keys and removes them,
     * then clears the tracking key itself.
     */
    public static function flush(): void
    {
        if (static::$deferFlush) {
            return;
        }

        if (! static::enabled()) {
            return;
        }

        $store = static::store();
        $trackingKey = static::key('_tracked_keys');
        $tracked = $store->get($trackingKey);

        if (is_array($tracked)) {
            foreach ($tracked as $key) {
                if (is_string($key)) {
                    $store->forget($key);
                }
            }
        }

        $store->forget($trackingKey);
    }

    /**
     * Track a cache key for later flushing.
     *
     * @param  string  $suffix  The cache key suffix to track.
     */
    protected static function track(string $suffix): void
    {
        if (! static::enabled()) {
            return;
        }

        $store = static::store();
        $trackingKey = static::key('_tracked_keys');
        $tracked = $store->get($trackingKey);
        $tracked = is_array($tracked) ? $tracked : [];
        $fullKey = static::key($suffix);

        if (! in_array($fullKey, $tracked, true)) {
            $tracked[] = $fullKey;
            $store->put($trackingKey, $tracked, static::ttl());
        }
    }

    /**
     * Get the configured cache key prefix.
     */
    protected static function prefix(): string
    {
        $prefix = config('laravel-turbo-tags.cache.key_prefix', 'turbo_tags');

        return is_string($prefix) ? $prefix : 'turbo_tags';
    }
}
