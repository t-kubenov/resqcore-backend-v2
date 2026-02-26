<?php

namespace App\Document\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    protected function cacheResponse(string $baseKey, Request $request, \Closure $callback, int $ttl = 10)
    {
        $cacheKey = $baseKey . ':' . md5(json_encode($request->all()));

        return Cache::remember($cacheKey, now()->addMinutes($ttl), $callback);
    }
}
