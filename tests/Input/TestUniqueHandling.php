<?php declare(strict_types=1);

namespace NZTim\Tests\Input;

use NZTim\Input\BaseProcessor;

class TestUniqueHandling extends BaseProcessor
{
    protected function rules(): array
    {
        return [];
    }

    public function processRules(array $rules): array
    {
        return $this->uniqueUpdates($rules);
    }
}
