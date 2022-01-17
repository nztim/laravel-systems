<?php declare(strict_types=1);

namespace NZTim\ORM;

use Carbon\Carbon;
use Laminas\Hydrator\Strategy\StrategyInterface;

class NullableCarbonDateStrategy implements StrategyInterface
{
    private string $format = 'Y-m-d';

    public function extract($value, ?object $object = null): ?string
    {
        return ($value instanceof Carbon) ? $value->format($this->format) : null;
    }

    public function hydrate($value, ?array $data): ?Carbon
    {
        return is_null($value) ? null : Carbon::createFromFormat($this->format, $value);
    }
}
