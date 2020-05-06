<?php

namespace Transprime\Arrayed\Interfaces;

use Countable;
use ArrayAccess;
use JsonSerializable;
use IteratorAggregate;

interface ArrayedInterface extends ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    public static function on(...$values): ArrayedInterface;

    public function map($callback): ArrayedInterface;

    public function filter($callback = null, int $flag = 0): ArrayedInterface;

    public function reduce($function, $initial = null): ArrayedInterface;

    public function merge(array $array2 = null, ...$_): ArrayedInterface;

    public function mergeRecursive(...$_): ArrayedInterface;

    public function flip(): ArrayedInterface;

    public function intersect(array $array2, ...$_): ArrayedInterface;

    public function values(): ArrayedInterface;

    public function keys($overwrite = true): ArrayedInterface;

    public function offsetGet($offset);

    public function offsetSet($offset, $value): ArrayedInterface;

    public function offsetUnset($offset): ArrayedInterface;

    public function sum(): int;

    public function contains($needle, bool $strict = false): bool;

    public function isArray(): bool;

    public function keyExists($key): bool;

    public function offsetExists($offset): bool;

    public function empty(): bool;

    public function count(): int;

    public function pipe(callable $action, ...$parameters);

    public function result(callable $callable = null);

    public function raw(): array;

    /**
     * @deprecated Use raw() instead
     */
    public function initial(): array;

    public function __toString(): string;

    public function copy(): ArrayedInterface;



    public function changeKeyCase(int $case = null): ArrayedInterface;

    public function chunk(int $size, bool $preserve_keys = false): ArrayedInterface;

    public function column($column, $index_key = null): ArrayedInterface;

    public function countValues(): ArrayedInterface;

    public function diffAssoc(array $array2, array ...$_): ArrayedInterface;

    public function diff(array $array2, array ...$_): ArrayedInterface;

    public function reverse(bool $preserve_keys = false): ArrayedInterface;


    /**
     * Like php array_key_exists, this instead search if (one or more) keys exists in the array
     *
     * @param array $needles - keys to look for in the array
     * @param bool $all - [Optional] if false then checks if at least one key is found
     * @return bool true if the needle(s) is found else false
     */
    public function keysExists(array $needles, bool $all = true): bool;
}
