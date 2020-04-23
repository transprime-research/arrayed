<?php

namespace Transprime\Arrayed;

use ArrayIterator;
use Transprime\Arrayed\Types\Undefined;

class Arrayed implements \ArrayAccess, \Countable, \IteratorAggregate
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
        return $this->result($callable);
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

    public function merge(array $array2 = null, ...$_): Arrayed
    {
        $this->lastResult = array_merge($this->getWorkableItem(), $array2, ...$_);

        return $this;
    }

    public function mergeRecursive(...$_): Arrayed
    {
        $this->lastResult = array_merge_recursive($this->getWorkableItem(), ...$_);

        return $this;
    }

    public function flip()
    {
        $this->lastResult = array_flip($this->getWorkableItem());

        return $this;
    }

    public function offsetGet($offset)
    {
        return $this->makeArrayed($this->getWorkableItem()[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        return $this->merge([$offset => $value]);
    }

    public function offsetUnset($offset)
    {
        $item = $this->getWorkableItem();

        unset($item[$offset]);

        $this->lastResult = $item;

        return $this;
    }

    //Scalar returns

    public function sum()
    {
        $this->lastResult = array_sum($this->getWorkableItem());

        return $this->lastResult;
    }

    public function inArray($needle, bool $strict = false): bool
    {
        return in_array($this->getWorkableItem(), $needle, $strict);
    }

    public function isArray(): bool
    {
        return is_array($this->getWorkableItem());
    }

    public function keyExists($key): bool
    {
        return array_key_exists($key, $this->getWorkableItem());
    }

    public function offsetExists($offset): bool
    {
        return $this->keyExists($offset);
    }

    public function empty(): bool
    {
        return empty($this->getWorkableItem());
    }

    public function count(): int
    {
        return count($this->getWorkableItem());
    }

    //Getters to end chained calls

    public function getIterator()
    {
        return new ArrayIterator($this->getWorkableItem());
    }

    public function result(callable $callable = null)
    {
        return $callable ? $callable($this->lastResult) : $this->getWorkableItem();
    }

    private function getWorkableItem(bool $asArray = false)
    {
        if ($this->lastResult instanceof Undefined) {
            return $this->values;
        }

        return ($asArray && !is_array($this->lastResult)) ? [$this->lastResult] : $this->lastResult;
    }

    private static function makeArrayed($data)
    {
        return is_array($data) ? new static($data) : $data;
    }
}
