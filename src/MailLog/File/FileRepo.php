<?php declare(strict_types=1);

namespace NZTim\MailLog\File;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use NZTim\MailLog\Entry\Entry;

class FileRepo
{
    private Filesystem $storage;

    public function __construct(FilesystemManager $fsManager)
    {
        $this->storage = $fsManager->disk('emails');
    }

    public function store(Entry $entry, string $html, string $text): void
    {
        $this->storage->put($entry->id() . ".html", $html);
        $this->storage->put($entry->id() . ".txt", $text);
    }

    public function getContent(Entry $entry): Content
    {
        $html = '';
        if ($this->storage->exists($entry->id() . '.html')) {
            $html = $this->storage->get($entry->id() . '.html');
        }
        $text = '';
        if ($this->storage->exists($entry->id() . '.txt')) {
            $text = $this->storage->get($entry->id() . '.txt');
        }
        return new Content($html, $text);
    }

    public function deleteContent(Entry $entry): void
    {
        if ($this->storage->exists($entry->id() . '.html')) {
            $this->storage->delete($entry->id() . '.html');
        }
        if ($this->storage->exists($entry->id() . '.txt')) {
            $this->storage->delete($entry->id() . '.txt');
        }
    }
}

