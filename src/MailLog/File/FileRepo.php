<?php declare(strict_types=1);

namespace NZTim\MailLog\File;

use Illuminate\Cache\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use NZTim\MailLog\Entry\Entry;

class FileRepo
{
    private Filesystem $storage;
    private Repository $cache;

    public function __construct(FilesystemManager $fsManager, Repository $cache)
    {
        $this->storage = $fsManager->disk('emails');
        $this->cache = $cache;
    }

    public function store(string $content, string $extension): string
    {
        $hash = md5($content);
        $path = substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . $hash . ".{$extension}";
        $this->storage->put($path, $content);
        return $path;
    }

    public function getHtml(Entry $entry): string
    {
        $key = 'maillog-html-' . $entry->id();
        return $this->cache->remember($key, now()->addDay(), function () use ($entry) {
            if ($this->storage->exists($entry->htmlFilePath())) {
                return $this->storage->get($entry->htmlFilePath());
            }
            return '';
        });
    }

    public function deleteContent(Entry $entry): void
    {
        if (!$entry->hasContent()) {
            return;
        }
        if ($this->storage->exists($entry->htmlFilePath())) {
            $this->storage->delete($entry->htmlFilePath());
        }
        if ($this->storage->exists($entry->textFilePath())) {
            $this->storage->delete($entry->textFilePath());
        }
    }
}

