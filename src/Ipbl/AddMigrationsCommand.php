<?php declare(strict_types=1);

namespace NZTim\Ipbl;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\MigrationCreator;

class AddMigrationsCommand extends Command
{
    protected $signature = 'ipbl:migration';

    protected $description = 'Add database migration for IPBL';

    public function handle()
    {
        $connection = config('database.ipbl');
        if (!$connection) {
            $this->error("Set config('database.ipbl') with name of SQLite database connection");
        }
        /** @var MigrationCreator $migrationCreator */
        $migrationCreator = app('migration.creator');
        $filename = $migrationCreator->create('create_ipbl_entries_table', database_path('migrations'));
        $content = file_get_contents(__DIR__ . '/add_entries_table.stub');
        $content = str_replace('%%CONNECTION%%', $connection, $content);
        file_put_contents($filename, $content);
    }
}
