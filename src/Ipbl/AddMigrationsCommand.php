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
        // entries table
        /** @var MigrationCreator $migrationCreator */
        $migrationCreator = app('migration.creator');
        $filename = $migrationCreator->create('create_ipbl_entries_table', database_path('migrations'));
        $stub = __DIR__ . '/add_entries_table.stub';
        file_put_contents($filename, file_get_contents($stub));
    }
}
