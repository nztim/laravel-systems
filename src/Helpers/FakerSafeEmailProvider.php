<?php declare(strict_types=1);

namespace NZTim\Helpers;

use Faker\Provider\Base;

class FakerSafeEmailProvider extends Base
{
    public function safeEmail(): string
    {
        return $this->generator->userName() . '@' . 'example.test';
    }
}
