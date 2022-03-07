<?php declare(strict_types=1);

namespace NZTim\MailLog\Entry\Persistence;

use NZTim\MailLog\Entry\Entry;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\NamingStrategy\UnderscoreNamingStrategy;
use NZTim\ORM\CarbonDateTimeStrategy;
use NZTim\ORM\JsonSerializeStrategy;
use ReflectionClass;

class EntryHydrator
{
    protected ReflectionHydrator $hydrator;

    public function __construct(ReflectionHydrator $hydrator)
    {
        $this->hydrator = $hydrator;
        $hydrator->setNamingStrategy(new UnderscoreNamingStrategy());
        $this->hydrator->addStrategy('date', new CarbonDateTimeStrategy());
        $this->hydrator->addStrategy('data', new JsonSerializeStrategy());
        $this->hydrator->addStrategy('created', new CarbonDateTimeStrategy());
    }

    public function extract(Entry $model): array
    {
        return $this->hydrator->extract($model);
    }

    public function hydrate(array $data): Entry
    {
        return $this->hydrator->hydrate($data, (new ReflectionClass(Entry::class))->newInstanceWithoutConstructor());
    }
}
