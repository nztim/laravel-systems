<?php declare(strict_types=1);

namespace NZTim\ORM\Laravel;

use Illuminate\Support\ServiceProvider;

class OrmServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AddPersistenceCommand::class,
            ]);
        }
    }
}
