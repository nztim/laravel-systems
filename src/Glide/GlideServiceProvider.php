<?php declare(strict_types=1);

namespace NZTim\Glide;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\Server;
use League\Glide\ServerFactory;

class GlideServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->bind(Server::class, function () {
            $storage = app(FilesystemManager::class);
            return ServerFactory::create([
                'response'       => new SymfonyResponseFactory(app('request')),
                'source'         => $storage->disk('glide_source')->getDriver(),
                'cache'          => $storage->disk('glide_cache')->getDriver(),
                'max_image_size' => 3000 * 2000,
            ]);
        });
    }
}
