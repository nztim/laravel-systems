<?php declare(strict_types=1);

namespace NZTim\Helpers;

use Illuminate\Console\Command;

class ServerCheckCommand extends Command
{
    protected $signature = 'server-conf-check {path=resources/server-conf/files.php}';
    protected $description = 'Check server configuration files against project versions';

    public function handle(): int
    {
        $filePath = base_path($this->argument('path'));
        $files = require $filePath;
        $folder = dirname($filePath);
        $failures = [];
        foreach ($files as $local => $serverPath) {
            $localPath = "{$folder}/{$local}";
            if (!file_exists($localPath) || !file_exists($serverPath)) {
                $failures[$local] = $serverPath;
                continue;
            }
            if (md5_file($localPath) !== md5_file($serverPath)) {
                $failures[$local] = $serverPath;
            }
        }
        if (count($failures)) {
            return $this->failed($failures);
        }
        $total = count($files);
        $this->info("Server configuration OK, {$total} files checked.");
        return 0;
    }

    private function failed(array $failures): int
    {
        $count = count($failures);
        $this->info("Server configuration check failed, {$count} files failed check:");
        foreach ($failures as $local => $serverPath) {
            $this->error("{$local} => {$serverPath}");
        }
        return 1;
    }
}
