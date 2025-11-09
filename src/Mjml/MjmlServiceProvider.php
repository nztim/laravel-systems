<?php declare(strict_types=1);

namespace NZTim\Mjml;

use Illuminate\Support\ServiceProvider;

class MjmlServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([MjmlCompileCommand::class]);
    }
}
