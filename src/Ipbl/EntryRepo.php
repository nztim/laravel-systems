<?php declare(strict_types=1);

namespace NZTim\Ipbl;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;

class EntryRepo
{
    private Connection $db;
    private string $table = 'ipbl';

    public function __construct(DatabaseManager $dbm)
    {
        $this->db = $dbm->connection(config('database.ipbl', config('database.default')));
    }

    public function add(string $ip, string $country, int $severity): void
    {
        $this->db->table($this->table)
            ->insert([
            'ip'      => $ip,
            'country' => $country,
            'points'  => $severity,
            'created' => now(),
        ]);
    }

    public function expireOld(int $days = 100): void
    {
        $this->db->table($this->table)
            ->where('created', '<', now()->subDays($days))
            ->delete();
    }

    /** @return []string of ip addresses */
    public function blocklist(): array
    {
        $results = $this->db->table($this->table)
            ->where('country', '!=', 'NZ')
            ->groupBy('ip')
            ->select($this->db->raw('ip, SUM(points) as points'))
            ->get();
        $blocklist = [];
        foreach ($results as $result) {
            if ($result->points >= 100) {
                $blocklist[] = $result->ip;
            }
        }
        return $blocklist;
    }
}
