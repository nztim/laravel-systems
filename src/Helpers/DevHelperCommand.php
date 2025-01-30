<?php declare(strict_types=1);

namespace NZTim\Helpers;

use Illuminate\Console\Command;

class DevHelperCommand extends Command
{
    protected $signature = 'dev-helper';
    protected $description = 'Run ide-helper commands if not in prod environment';

    public function handle()
    {
        if (!app()->isProduction()) {
            $this->info('Running ide-helper generate and meta...');
            $this->call('ide-helper:generate');
            $this->call('ide-helper:meta');
        }
    }
}
