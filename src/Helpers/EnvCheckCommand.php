<?php namespace NZTim\Helpers;

use Illuminate\Console\Command;

class EnvCheckCommand extends Command
{
    protected $signature = 'envcheck';
    protected $description = 'Check .env file against .env.example';

    public function handle()
    {
        $env = $this->transform(file_get_contents(base_path('.env')));
        $example = $this->transform(file_get_contents(base_path('.env.example')));
        $missing = [];
        $extra = [];
        foreach ($example as $key => $value) {
            if (!isset($env[$key])) {
                $missing[] = $key;
            }
        }
        foreach ($env as $key => $value) {
            if (!isset($example[$key])) {
                $extra[] = $key;
            }
        }
        if ($missing === [] && $extra === []) {
            $this->info('.env file check OK');
            return;
        }
        $output = [];
        foreach ($missing as $item) {
            $output[] = [$item, 'Missing from .env'];
        }
        foreach ($extra as $item) {
            $output[] = [$item, 'Not in .env.example'];
        }
        $this->line('');
        $this->error('*** WARNING ***');
        $this->error('.env check failed!');
        $this->table(['Key', 'Error'], $output);
    }

    protected function transform(string $file): array
    {
        $lines = explode("\n", $file);
        $data = [];
        foreach ($lines as $line) {
            if (empty(trim($line)) || preg_match('|^#|', $line)) {
                continue;
            }
            $values = explode('=', $line);
            $data[$values[0]] = $values[1] ?? null;
        }
        return $data;
    }
}
