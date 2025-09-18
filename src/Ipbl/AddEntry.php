<?php declare(strict_types=1);

namespace NZTim\Ipbl;

class AddEntry
{
    public string $ip;
    public int $severity;
    public string $reason;

    public function __construct(string $ip, int $severity, string $reason)
    {
        $this->ip = $ip;
        $this->severity = $severity;
        $this->reason = $reason;
    }
}
