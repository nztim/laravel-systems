<?php declare(strict_types=1);

namespace NZTim\Ipbl\Entry;

use Carbon\Carbon;

class Entry
{
    public int|null $id;
    public string $ip;
    public string $country;
    public int $points;
    public Carbon $created;
    public Carbon $updated;

    public function __construct(string $ip, string $country)
    {
        $this->id = null;
        $this->ip = $ip;
        $this->country = $country;
        $this->points = 0;
        $this->created = now();
        $this->updated = now();
    }
}
