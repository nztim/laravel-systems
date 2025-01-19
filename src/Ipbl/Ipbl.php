<?php declare(strict_types=1);

namespace NZTim\Ipbl;

use Illuminate\Http\Request;
use NZTim\Geolocate\Geolocate;
use NZTim\Ipbl\Entry\Entry;
use NZTim\Ipbl\Entry\Persistence\EntryRepo;

class Ipbl
{
    private EntryRepo $entryRepo;
    private Geolocate $geolocate;

    public function __construct(EntryRepo $entryRepo, Geolocate $geolocate)
    {
        $this->entryRepo = $entryRepo;
        $this->geolocate = $geolocate;
    }

    public function add(string $ip, int $severity, string $reason): void
    {
        $entry = $this->entryRepo->findByIp($ip);
        if (!$entry) {
            $entry = new Entry($ip, $this->geolocate->fromIp($ip));
        }
        $entry->points += $severity;
        $this->entryRepo->persist($entry);
        log_info('ipbl', "{$ip} | {$entry->country} | {$severity} | {$reason} ");
    }

    public function evaluate404(Request $request, int $severity = 5): void
    {
        $path = $request->path();
        if ($this->badPath($path)) {
            $this->add($request->ip(), $severity, "Bad 404: {$path}");
        }
    }

    private function badPath(string $path): bool
    {
        $paths = [
            '.env',
            '/wp/wp-includes/wlwmanifest.xml',
            '/xmlrpc.php',
            'admin.zip',
            'admin/.env',
            'administrator.zip',
            'api/.env',
            'app/.env',
            'app/config/.env',
            'app/etc/env.php',
            'aws_credentials',
            'backup.zip',
            'backup/config.php',
            'backups/.env',
            'base/.env',
            'blog/.env',
            'blog/wp-login.php',
            'config/aws_config.json',
            'config/aws_credentials.json',
            'database.zip',
            'dump.sql',
            'front/.env',
            'keys/aws_keys.json',
            'laravel/.env',
            'library/.env',
            'local/.env',
            'new/.env',
            'old/.env',
            'prod/.env',
            'public/.env',
            'secrets/aws.json',
            'secrets/aws_config',
            'secrets/aws_credentials',
            'settings.json',
            'storage/.env',
            'storage/aws.json',
            'storage/logs/laravel.log',
            'var/log/system.log',
            'vendor/.env',
            'vendor/laravel/.env',
            'wp-admin/.env',
            'wp-config.php',
            'wp-content/.env',
            'wp-login.php',
            'www/.env',
            'xmlrpc.php',
        ];
        return in_array($path, $paths);
    }
}

