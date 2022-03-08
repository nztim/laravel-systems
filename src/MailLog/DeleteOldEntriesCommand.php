<?php declare(strict_types=1);

namespace NZTim\MailLog;

use Illuminate\Console\Command;
use NZTim\MailLog\Entry\Entry;
use NZTim\MailLog\Entry\Persistence\EntryRepo;
use NZTim\MailLog\File\FileRepo;

class DeleteOldEntriesCommand extends Command
{
    protected $signature = 'maillog:prune';
    protected $description = 'Delete old mail log entries';

    public function handle()
    {
        /** @var EntryRepo $entryRepo */
        $entryRepo = app(EntryRepo::class);
        $entries = $entryRepo->findOlderThanDays(now()->subDays(14));
        /** @var FileRepo $fileRepo */
        $fileRepo = app(FileRepo::class);
        foreach ($entries as $entry) { /** @var Entry $entry */
            $fileRepo->deleteContent($entry);
            $entryRepo->delete($entry);
        }
    }
}
