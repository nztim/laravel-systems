<?php declare(strict_types=1);

namespace NZTim\Glide;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use League\Glide\Server;

class GlideServer
{
    private FilesystemManager $storage;
    private Server $server;

    public function __construct(FilesystemManager $storage, Server $server)
    {
        $this->storage = $storage;
        $this->server = $server;
    }

    public function storeImage(UploadedFile $file, GlideImage $image)
    {
        if (!$this->storage->disk('glide_source')->exists($image->path(true))) {
            $this->storage->disk('glide_source')->putFileAs($image->path(false), $file, $image->filename());
        }
    }

    public function storeImageData(string $data, GlideImage $image)
    {
        if (!$this->storage->disk('glide_source')->exists($image->path())) {
            $this->storage->disk('glide_source')->put($image->path(), $data);
        }
    }

    public function makeImage(GlideImage $image, array $params)
    {
        $this->server->makeImage($image->path(), $params);
    }

    public function getImageResponse(GlideImage $image, array $params)
    {
        return $this->server->getImageResponse($image->path(), $params);
    }
}
