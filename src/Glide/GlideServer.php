<?php declare(strict_types=1);

namespace NZTim\Glide;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use League\Glide\Server;
use RuntimeException;

class GlideServer
{
    private FilesystemManager $storage;
    private Server $server;

    public function __construct(FilesystemManager $storage, Server $server)
    {
        $this->storage = $storage;
        $this->server = $server;
    }

    public function storeImage(UploadedFile $file, GlideImage $image): void
    {
        if (!$this->storage->disk('glide_source')->exists($image->path())) {
            $result = $this->storage->disk('glide_source')->putFileAs($image->path(false), $file, $image->filename());
            if ($result === false) {
                throw new RuntimeException('GlideServer::storeImage file write error.');
            }
        }
    }

    public function storeImageData(string $data, GlideImage $image): void
    {
        if (!$this->storage->disk('glide_source')->exists($image->path())) {
            $result = $this->storage->disk('glide_source')->put($image->path(), $data);
            if ($result === false) {
                throw new RuntimeException('GlideServer::storeImageData file write error.');
            }
        }
    }

    public function makeImage(GlideImage $image, array $params): void
    {
        $this->server->makeImage($image->path(), $params);
    }

    public function getImageResponse(GlideImage $image, array $params)
    {
        return $this->server->getImageResponse($image->path(), $params);
    }
}
