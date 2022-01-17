<?php declare(strict_types=1);

namespace NZTim\ORM;

use Laminas\Hydrator\Strategy\StrategyInterface;

class BooleanStrategy implements StrategyInterface
{
    public function extract($value, ?object $object = null)
    {
        return $value ? 1 : 0;
    }

    public function hydrate($value, ?array $data)
    {
        return boolval($value);
    }
}
