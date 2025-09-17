<?php declare(strict_types=1);

namespace NZTim\Ipbl\Entry\Persistence;

use NZTim\Ipbl\Entry\Entry;
use Illuminate\Database\Connection;
use Illuminate\Support\Collection;
use stdClass;

class EntryRepo
{
    private EntryHydrator $hydrator;
    private Connection $db;
    private string $table = 'ipbl';

    public function __construct(EntryHydrator $hydrator, Connection $db)
    {
        $this->hydrator = $hydrator;
        $this->db = $db;
    }

    // Read -------------------------------------------------------------------

    public function findById(int $id): Entry|null
    {
        $row = $this->db->table($this->table)->find($id);
        return $row ? $this->hydrate($row) : null;
    }

    public function findByIp(string $ip): Entry|null
    {
        $row = $this->db->table($this->table)->where('ip', $ip)->first();
        return $row ? $this->hydrate($row) : null;
    }

    public function add(string $ip, string $country, int $severity): void
    {
        $this->db->transaction(function () use ($ip, $country, $severity) {
            $entry = $this->findByIp($ip);
            if (!$entry) {
                $entry = new Entry($ip, $country);
            }
            $entry->points += $severity;
            $this->persist($entry);
        });
    }

    /** @return Entry[]|Collection */
    public function toBlock(): Collection
    {
        $rows = $this->db->table($this->table)
            ->where('country', '!=', 'NZ')
            ->where('points', '>=', 100)
            ->get();
        return $this->hydrateCollection($rows);
    }

    // Write ------------------------------------------------------------------

    public function persist(Entry $model): int
    {
        $model->updated = now();
        $data = $this->hydrator->extract($model);
        if (is_null($model->id)) {
            return $this->db->table($this->table)->insertGetId($data);
        }
        $this->db->table($this->table)->where('id', $model->id)->update($data);
        return $model->id;
    }

    public function persistAndReturn(Entry $model): Entry
    {
        $id = $this->persist($model);
        return $this->findById($id);
    }

    public function expireOld(int $days = 180): void
    {
        $this->db->table($this->table)
            ->where('updated', '<', now()->subDays($days))
            ->update(['points' => 0]);
    }

    // Hydrate ----------------------------------------------------------------

    private function hydrate(stdClass $data): Entry
    {
        return $this->hydrator->hydrate((array)$data);
    }

    /** @return Entry[]|Collection */
    private function hydrateCollection(Collection $collection): Collection
    {
        return $collection->map(function (stdClass $data) {
            return $this->hydrate($data);
        });
    }
}
