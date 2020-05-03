<?php

namespace Transprime\Arrayed\Traits;

use Transprime\Arrayed\Interfaces\ArrayedInterface;

trait ArrayPrefix
{
    public function changeKeyCase(int $case = null): ArrayedInterface
    {
        return $this->setResult(array_change_key_case($this->getWorkableItem(), $case));
    }
}