<?php declare(strict_types=1);

namespace NZTim\Ipbl;

use Illuminate\Http\Request;
use NZTim\Geolocate\Geolocate;

class Ipbl
{
    private EntryRepo $entryRepo;
    private Geolocate $geo;

    public function __construct(EntryRepo $entryRepo, Geolocate $geo)
    {
        $this->entryRepo = $entryRepo;
        $this->geo = $geo;
    }

    public function add(string $ip, int $severity, string $reason): void
    {
        $country = $this->geo->fromIp($ip);
        $this->entryRepo->add($ip, $country, $severity);
        log_info('ipbl', "{$ip} | {$country} | {$severity} | {$reason} ");
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
            '.env.backup',
            '/wp/wp-includes/wlwmanifest.xml',
            '/xmlrpc.php',
            '0.php',
            '0.php',
            '02.php',
            '031.php',
            '1.php',
            '1.php',
            '10.php',
            '11.php',
            '11.php',
            '12.php',
            '123.php',
            '123.php',
            '2.php',
            '222.php',
            '222.php',
            '3.php',
            '333.php',
            '406.php',
            '444.php',
            '5.php',
            '7.php',
            'admin.php',
            'admin.zip',
            'admin/.env',
            'admin/.env',
            'administrator.env',
            'administrator.zip',
            'api/.env',
            'app/.env',
            'app/config/.env',
            'app/etc/env.php',
            'application/.env',
            'apps/.env',
            'assets/.env',
            'aws-secret.yaml',
            'aws_credentials',
            'backend/.env',
            'backend/.env',
            'backup.php',
            'backup.sql',
            'backup.zip',
            'backup/config.php',
            'backup2.php',
            'backups/.env',
            'base/.env',
            'blog/.env',
            'blog/wp-login.php',
            'conf/.env',
            'config/.env',
            'config/aws_config.json',
            'config/aws_credentials.json',
            'config/production.json',
            'core/.env',
            'core/app/.env',
            'cron/.env',
            'database.zip',
            'dev/.env',
            'development/.env',
            'docker-compose.yml',
            'docker/.env',
            'docker/app/.env',
            'dump.sql',
            'env.backup',
            'front/.env',
            'keys/aws_keys.json',
            'lab/.env',
            'laravel/.env',
            'laravel/core/.env',
            'lib/.env',
            'library/.env',
            'local/.env',
            'new/.env',
            'new/.env.local',
            'new/.env.production',
            'old/.env',
            'pass.php',
            'prod/.env',
            'public/.env',
            'saas/.env',
            'secrets.json',
            'secrets/aws.json',
            'secrets/aws_config',
            'secrets/aws_credentials',
            'settings.json',
            'site/.env',
            'sitemaps/.env',
            'storage/.env',
            'storage/aws.json',
            'storage/logs/laravel.log',
            'tools/.env',
            'uploads/.env',
            'var/log/system.log',
            'vendor/.env',
            'vendor/laravel/.env',
            'web/.env',
            'wp-admin/.env',
            'wp-config.php',
            'wp-content/.env',
            'wp-login.php',
            'www/.env',
            'x.php',
            'xmlrpc.php',
            'xo.php',
            'xx.php',
            'xx.php',
        ];
        return in_array($path, $paths);
    }
}

