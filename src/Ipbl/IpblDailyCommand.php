<?php declare(strict_types=1);

namespace NZTim\Ipbl;

use Illuminate\Console\Command;
use NZTim\Ipbl\Entry\Persistence\EntryRepo;

class IpblDailyCommand extends Command
{
    protected $signature = 'ipbl:daily';

    protected $description = 'Expire old IPBL entries and write updated list to disk';

    public function handle()
    {
        $this->entryRepo()->expireOld();
        $output = '';
        foreach ($this->entryRepo()->toBlock() as $entry) {
            $output .= "Require not ip {$entry->ip}\n";
        }
        file_put_contents(storage_path('app/ipbl.conf'), $output);
    }

    private function entryRepo(): EntryRepo
    {
        return app(EntryRepo::class);
    }
}
