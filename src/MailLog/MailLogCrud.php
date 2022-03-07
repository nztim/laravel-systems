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
        $entry = Entry::createFromMessageSent($messageSent);
        $id = $this->entryRepo->persist($entry);
        $entry = $this->entryRepo->findById($id);
        $this->fileRepo->store($entry, $messageSent->html(), $messageSent->text());
    }
}
