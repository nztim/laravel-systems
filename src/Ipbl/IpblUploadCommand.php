<?php declare(strict_types=1);

namespace NZTim\Ipbl;

use Illuminate\Console\Command;
use NZTim\SimpleHttp\Http;

class IpblUploadCommand extends Command
{
    protected $signature = 'ipbl:upload';

    protected $description = 'Upload blocklist to central server.';

    public function handle()
    {
        $this->entryRepo()->expireOld();
        $blocklist = $this->entryRepo()->blocklist();
        (new Http())
            ->withHeaders(['X-API-KEY' => config('services.ipbl.key')])
            ->post(config('services.ipbl.url'), ['list' => $blocklist]);
    }

    private function entryRepo(): EntryRepo
    {
        return app(EntryRepo::class);
    }
}
