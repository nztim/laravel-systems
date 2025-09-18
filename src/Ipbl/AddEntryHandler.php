<?php declare(strict_types=1);

namespace NZTim\Ipbl;

use NZTim\Geolocate\Geolocate;
use NZTim\Ipbl\Entry\Persistence\EntryRepo;

class AddEntryHandler
{
    private Geolocate $geolocate;
    private EntryRepo $entryRepo;

    public function __construct(Geolocate $geolocate, EntryRepo $entryRepo)
    {
        $this->geolocate = $geolocate;
        $this->entryRepo = $entryRepo;
    }

    public function handle(AddEntry $command): void
    {
        $country = $this->geolocate->fromIp($command->ip);
        $this->entryRepo->add($command->ip, $country, $command->severity);
        log_info('ipbl', "{$command->ip} | {$country} | {$command->severity} | {$command->reason} ");
    }
}
