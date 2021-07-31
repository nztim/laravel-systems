<?php declare(strict_types=1);

namespace NZTim\Logger;

use Illuminate\Contracts\Cache\Repository;

class LaravelCache implements Cache
{
    private Repository $cache;

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    public function has(string $key): bool
    {
        return $this->cache->has($key);
    }

    public function put(string $key, $value, int $minutes): void
    {
        $this->cache->put($key, $value, now()->addMinutes($minutes));
    }
}
