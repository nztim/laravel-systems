<?php declare(strict_types=1);

namespace NZTim\Ipbl;

use Illuminate\Console\Command;

class IpblDailyCommand extends Command
{
    protected $signature = 'ipbl:daily';

    protected $description = 'Expire old IPBL entries and write updated list to disk';

    public function handle()
    {
        $this->entryRepo()->expireOld();
        $output = '';
        foreach ($this->entryRepo()->blocklist() as $ip) {
            $output .= "Require not ip {$ip}\n";
        }
        file_put_contents(storage_path('app/ipbl.conf'), $output);
    }

    private function entryRepo(): EntryRepo
    {
        return app(EntryRepo::class);
    }
}
