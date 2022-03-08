<?php declare(strict_types=1);

namespace NZTim\MailLog\Entry\Persistence;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use NZTim\MailLog\Entry\Entry;
use Illuminate\Database\Connection;
use Illuminate\Support\Collection;
use stdClass;

class EntryRepo
{
    private EntryHydrator $hydrator;
    private Connection $db;
    private string $table = 'mail_log';

    public function __construct(EntryHydrator $hydrator, Connection $db)
    {
        $this->hydrator = $hydrator;
        $this->db = $db;
    }

    // Read -------------------------------------------------------------------

    public function findById(int $id): ?Entry
    {
        $row = $this->db->table($this->table)->find($id);
        return $row ? $this->hydrate($row) : null;
    }

    public function findOlderThanDays(Carbon $date): Collection
    {
        $rows = $this->db->table($this->table)->where('date', '<', $date)->get();
        return $this->hydrateCollection($rows);
    }

    public function index(string $search = '', string $type = ''): LengthAwarePaginator
    {
        $query = $this->db->table($this->table);
        if (strlen($search)) {
            $query = $query->where('recipient', 'LIKE', "%{$search}%");
        }
        if (strlen($type)) {
            $query = $query->where('type', $type);
        }
        $results = $query->orderBy('date', 'desc')->paginate(25);
        return $results->setCollection($this->hydrateCollection($results->getCollection()));
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

    // Write ------------------------------------------------------------------

    public function persist(Entry $model): int
    {
        $data = $this->hydrator->extract($model);
        if (is_null($model->id())) {
            return $this->db->table($this->table)->insertGetId($data);
        }
        $this->db->table($this->table)->where('id', $model->id())->update($data);
        return $model->id();
    }

    public function delete(Entry $entry): void
    {
        $this->db->table($this->table)->where('id', $entry->id())->delete();
    }
}
