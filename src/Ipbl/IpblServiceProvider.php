<?php declare(strict_types=1);

namespace NZTim\Ipbl;

use Illuminate\Support\ServiceProvider;

class IpblServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            AddMigrationsCommand::class,
            IpblDailyCommand::class,
        ]);
    }
}
