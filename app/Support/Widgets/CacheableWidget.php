<?php

namespace App\Support\Widgets;

use Illuminate\Support\Facades\Cache;

trait CacheableWidget
{
    /**
     * Cache a computed value for the widget using a namespaced key.
     */
    protected function rememberWidget(string $key, int $ttlSeconds, \Closure $callback)
    {
        $cacheKey = static::class . ':' . $key;
        return Cache::remember($cacheKey, $ttlSeconds, $callback);
    }
}


