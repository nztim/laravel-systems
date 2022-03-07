<?php declare(strict_types=1);

namespace NZTim\MailLog;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\MigrationCreator;

class AddMigrationCommand extends Command
{
    protected $signature = 'maillog:migration';

    protected $description = 'Add database migration for MailLog';

    public function handle()
    {
        /** @var MigrationCreator $migrationCreator */
        $migrationCreator = app('migration.creator');
        $filename = $migrationCreator->create('create_mail_log_table', database_path('migrations'));
        // Overwrite with migration content
        $stub = __DIR__. '/migration.stub';
        file_put_contents($filename, file_get_contents($stub));
    }
}
