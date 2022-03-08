<?php declare(strict_types=1);

namespace NZTim\MailLog;

use NZTim\Mailer\MessageSent;
use NZTim\MailLog\Entry\Entry;
use NZTim\MailLog\Entry\Persistence\EntryRepo;
use NZTim\MailLog\File\FileRepo;

class MailLogCrud
{
    private EntryRepo $entryRepo;
    private FileRepo $fileRepo;

    public function __construct(EntryRepo $entryRepo, FileRepo $fileRepo)
    {
        $this->entryRepo = $entryRepo;
        $this->fileRepo = $fileRepo;
    }

    public function createFromMessageSent(MessageSent $messageSent): void
    {
        $htmlPath = $this->fileRepo->store($messageSent->html(), 'html');
        $textPath = $this->fileRepo->store($messageSent->text(), 'txt');
        $entry = Entry::fromMessageSent($messageSent, $htmlPath, $textPath);
        $this->entryRepo->persist($entry);
    }
}
