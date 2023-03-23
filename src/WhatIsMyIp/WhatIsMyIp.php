<?php declare(strict_types=1);

namespace NZTim\WhatIsMyIp;

use Illuminate\Contracts\Cache\Repository;

class WhatIsMyIp
{
    private Repository $cache;

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    public function get(): string
    {
        return $this->cache->remember('whatismyip', now()->addDay(), function () {
            return trim(file_get_contents('https://api.ipify.org'));
        });
    }
}

/*
 * Alternatively use gethostbyname() with domain extracted from config('app.url')
 */
