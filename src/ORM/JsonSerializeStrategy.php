<?php declare(strict_types=1);

namespace NZTim\ORM;

use Laminas\Hydrator\Strategy\StrategyInterface;

class JsonSerializeStrategy implements StrategyInterface
{
    public function extract($value, ?object $object = null)
    {
        return json_encode($value);
    }

    public function hydrate($value, ?array $data)
    {
        return json_decode($value, true);
    }
}
