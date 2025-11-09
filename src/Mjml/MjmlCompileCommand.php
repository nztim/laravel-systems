<?php declare(strict_types=1);

namespace NZTim\Mjml;

use Illuminate\Console\Command;
use NZTim\SimpleHttp\Http;

class MjmlCompileCommand extends Command
{
    protected $signature = 'mjml:compile {file}';

    protected $description = 'Compile MJML file into HTML';

    public function handle(): int
    {
        // Normalise file path and validate
        $file = realpath($this->argument('file'));
        if (!$file) {
            $this->error("File does not exist: {$file}");
            return 1;
        }
        if (!is_file($file)) {
            $this->error("Path is not a file: {$file}");
            return 1;
        }
        $pathinfo = pathinfo($file);
        if ($pathinfo['extension'] !== 'mjml') {
            $this->error("File does not have .mjml extension: {$file}");
            return 1;
        }
        // Get MJML and process
        $html = $this->processMjml(file_get_contents($file));
        if (is_null($html)) {
            return 1;
        }
        // Write HTML to file
        $outputFile = "{$pathinfo['dirname']}" . DIRECTORY_SEPARATOR . "{$pathinfo['filename']}.blade.php";
        file_put_contents($outputFile, $html);
        $this->info("Processed and wrote file: {$outputFile}");
        return 0;
    }

    private function processMjml(string $mjml): string|null
    {
        $response = (new Http())
            ->withBasicAuth(config('services.mjml.app_id'), config('services.mjml.secret'))
            ->post(config('services.mjml.url'), ['mjml' => $mjml]);
        if ($response->status() !== 200) {
            $this->error("API error, status {$response->status()}, body: {$response->body()}");
            return null;
        }
        return $response->json()['html'];
    }
}
