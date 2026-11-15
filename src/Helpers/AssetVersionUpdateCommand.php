<?php declare(strict_types=1);

namespace NZTim\Helpers;

use Illuminate\Console\Command;

class AssetVersionUpdateCommand extends Command
{
    protected $signature = 'asset-version:update';
    protected $description = 'Update ASSET_VERSION .env variable with new value';

    public function handle(): int
    {
        $content = file_get_contents(base_path('.env'));
        if (!str_contains($content, 'ASSET_VERSION')) {
            $this->info('No ASSET_VERSION .env var found');
            return 0;
        }
        file_put_contents(base_path('.env'), preg_replace('/^ASSET_VERSION=.*$/m', 'ASSET_VERSION='.  substr(md5(str_random()), 0, 8),  $content));
        return 0;
    }
}
