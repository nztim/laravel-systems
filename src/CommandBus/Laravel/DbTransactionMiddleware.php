<?php

namespace NZTim\CommandBus\Laravel;

use Illuminate\Database\Connection;
use NZTim\CommandBus\Middleware;
use Throwable;

class DbTransactionMiddleware implements Middleware
{
    protected Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function execute(object $command, callable $next)
    {
        $this->db->beginTransaction();
        try {
            $val = $next($command);
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
        return $val;
    }
}
