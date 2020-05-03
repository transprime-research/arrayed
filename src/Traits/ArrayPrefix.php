<?php

namespace Transprime\Arrayed\Traits;

use Transprime\Arrayed\Interfaces\ArrayedInterface;

trait ArrayPrefix
{
    public function changeKeyCase(int $case = null): ArrayedInterface
    {
        return $this->setResult(array_change_key_case($this->getWorkableItem(), $case));
    }

    public function chunk(int $size, bool $preserve_keys = false): ArrayedInterface
    {
        return $this->setResult(array_chunk($this->getWorkableItem(), $size, $preserve_keys));
    }
}