<?php

namespace Modules\Applications\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Store data in cache with optional tags
     */
    public function remember(string $key, callable $callback, array $tags = [], int $ttl = 3600)
    {
        try {
            if (!empty($tags)) {
                return Cache::tags($tags)->remember($key, $ttl, $callback);
            }
            
            return Cache::remember($key, $ttl, $callback);
            
        } catch (\Exception $e) {
            Log::error('Cache error for key: ' . $key, ['error' => $e->getMessage()]);
            return $callback();
        }
    }

    /**
     * Store data in cache forever with tags
     */
    public function rememberForever(string $key, callable $callback, array $tags = [])
    {
        try {
            if (!empty($tags)) {
                return Cache::tags($tags)->rememberForever($key, $callback);
            }
            
            return Cache::rememberForever($key, $callback);
            
        } catch (Exception $e) {
            Log::error('Cache forever error for key: ' . $key, ['error' => $e->getMessage()]);
            return $callback();
        }
    }

    /**
     * Forget cache by key
     */
    public function forget(string $key, array $tags = []): bool
    {
        try {
            if (!empty($tags)) {
                return Cache::tags($tags)->forget($key);
            }
            
            return Cache::forget($key);
            
        } catch (Exception $e) {
            Log::error('Cache forget error for key: ' . $key, ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Clear cache by tags
     */
    public function clearTags(array $tags): void
    {
        try {
            Cache::tags($tags)->flush();
        } catch (Exception $e) {
            Log::error('Cache clear tags error', ['tags' => $tags, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Generate cache key with prefix
     */
    public function generateKey(string $prefix, array $parameters = []): string
    {
        $paramsString = !empty($parameters) ? '_' . md5(json_encode($parameters)) : '';
        return $prefix . $paramsString;
    }
}