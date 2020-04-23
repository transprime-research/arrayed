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

    public function filter($callback = null, int $flag = 0): Arrayed
    {
        if ($callback) {
            $this->lastResult = array_filter($this->getWorkableItem(), $callback, $flag);
        } else {
            $this->lastResult = array_filter($this->getWorkableItem());
        }

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

    public function flip(): Arrayed
    {
        $this->lastResult = array_flip($this->getWorkableItem());

        return $this;
    }

    public function intersect(array $array2, ...$_): Arrayed
    {
        $this->lastResult = array_intersect($this->getWorkableItem(), $array2, ...$_);

        return $this;
    }

    public function values(): Arrayed
    {
        $this->lastResult = array_values($this->getWorkableItem());

        return $this;
    }

    public function keys($overwrite = true): Arrayed
    {
        $keys = array_keys($this->getWorkableItem());

        if (!$overwrite) {
            return $this->makeArrayed($keys);
        }

        $this->lastResult = $keys;

        return $this;
    }

    public function offsetGet($offset)
    {
        return $this->makeArrayed($this->getWorkableItem()[$offset]);
    }

    public function offsetSet($offset, $value): Arrayed
    {
        return $this->merge([$offset => $value]);
    }

    public function offsetUnset($offset): Arrayed
    {
        $item = $this->getWorkableItem();

        unset($item[$offset]);

        $this->lastResult = $item;

        return $this;
    }

    //Scalar returns

    public function sum(): int
    {
        return array_sum($this->getWorkableItem());
    }

    public function contains($needle, bool $strict = false): bool
    {
        return in_array($needle, $this->getWorkableItem(), $strict);
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
