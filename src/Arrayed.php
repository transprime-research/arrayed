<?php

namespace Transprime\Arrayed;

use Transprime\Arrayed\Exceptions\ArrayedException;
use Transprime\Arrayed\Types\Undefined;

class Arrayed
{
    private array $values;

    private $lastResult;

    public function __construct(...$values)
    {

        if (func_num_args() === 1 && is_array($values[0])) {
            $this->values = $values[0];
        } else {
            $this->values = $values;
        }

        $this->lastResult = new Undefined();
    }

    public function __invoke(callable $callable = null)
    {
        return $this->done($callable);
    }

    public function map($callback): Arrayed
    {
        $this->lastResult = array_map($callback, $this->getWorkableItem());

        return $this;
    }

    public function filter(callable $callback = null, $flag = 0): Arrayed
    {
        $this->lastResult = array_filter($this->getWorkableItem(), $callback, $flag);

        return $this;
    }

    public function reduce($function, $initial = null): Arrayed
    {
        $this->lastResult = array_reduce($this->getWorkableItem(), $function, $initial);

        return $this;
    }

    public function sum(): Arrayed
    {
        $this->lastResult = array_sum($this->getWorkableItem());

        return $this;
    }

    public function done(callable $callable = null)
    {
        return $callable ? $callable($this->lastResult) : $this->lastResult;
    }

    private function getWorkableItem()
    {
        if ($this->lastResult instanceof Undefined) {
            return $this->values;
        }

        return $this->lastResult;
    }
}
