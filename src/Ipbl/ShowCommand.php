<?php declare(strict_types=1);

namespace NZTim\Ipbl;

use Illuminate\Console\Command;

class ShowCommand extends Command
{
    protected $signature = 'ipbl:show';
    protected $description = 'Show current blocklist with point totals';

    public function handle()
    {
        $table = [];
        foreach (app(EntryRepo::class)->list() as $ip => $total) {
            $table[] = [$ip, $total];
        }
        $this->table(['IP', 'Points'], $table);
    }
}
