<?php declare(strict_types=1);

namespace NZTim\MailLog\Examples;

use NZTim\MailLog\Entry\Entry;
use NZTim\MailLog\Entry\Persistence\EntryRepo;
use NZTim\MailLog\File\FileRepo;

class SystemMessagesController
{
    private EntryRepo $entryRepo;
    private FileRepo $fileRepo;

    public function __construct(EntryRepo $entryRepo, FileRepo $fileRepo)
    {
        $this->entryRepo = $entryRepo;
        $this->fileRepo = $fileRepo;
    }

    public function index()
    {
        $search = request('search', '');
        $type = request('type', '');
        $entries = $this->entryRepo->index($search, $type);
        $types = Entry::typeSelect('All types');
        return view('admin.system-messages.index')
            ->with('entries', $entries)
            ->with('types', $types)
            ->with('type', $type)
            ->with('search', $search);
    }

    public function show($id)
    {
        $entry = $this->entryRepo->findById(intval($id));
        if (!$entry) {
            abort(404);
        }
        return $this->fileRepo->getHtml($entry);
    }
}
