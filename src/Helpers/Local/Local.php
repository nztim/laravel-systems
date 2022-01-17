<?php namespace NZTim\Helpers\Local;

use Illuminate\Contracts\Foundation\Application;

class Local
{
    public function bootstrap(Application $app)
    {
        $file = base_path('local.php');
        if (file_exists($file)) {
            $app->singleton('nztim-helpers-local', function () use ($file) {
                return new \Illuminate\Config\Repository(require $file);
            });
        }
        require __DIR__.'/helper.php';
    }
}
