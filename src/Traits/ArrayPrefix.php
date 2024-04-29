<?php

declare(strict_types=1);

namespace Transprime\Arrayed\Traits;

use Transprime\Arrayed\Exceptions\ArrayedException;
use Transprime\Arrayed\Interfaces\ArrayedInterface;

/**
 * Trait ArrayPrefix
 * @package Transprime\Arrayed\Traits
 *
 * @method self combine(array $values)
 * @method mixed shift()
 * @method mixed pop()
 * @method self slice(int $offset, int $length = null, bool $preserve_keys = false)
 */
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

    public function column($column, $index_key = null): ArrayedInterface
    {
        return $this->setResult(array_column($this->getWorkableItem(), $column, $index_key));
    }

    public function countValues(): ArrayedInterface
    {
        return $this->setResult(array_count_values($this->getWorkableItem()));
    }

    public function diffAssoc(array $array2, array ...$_): ArrayedInterface
    {
        return $this->setResult(array_diff_assoc($this->getWorkableItem(), $array2, ...$_));
    }

    public function diff(array $array2, array ...$_): ArrayedInterface
    {
        return $this->setResult(array_diff($this->getWorkableItem(), $array2, ...$_));
    }

    public function reverse(bool $preserve_keys = false): ArrayedInterface
    {
        return $this->setResult(array_reverse($this->getWorkableItem(), $preserve_keys));
    }

    public function diffUassoc(callable $key_compare_func, array $array2, array ...$_): ArrayedInterface
    {
        return $this->setResult(array_diff_uassoc($this->getWorkableItem(), $array2, ...$_, ...[$key_compare_func]));
    }

    public function diffKey(array $array2, array ...$_): ArrayedInterface
    {
        return $this->setResult(array_diff_key($this->getWorkableItem(), $array2, ...$_));
    }

    /**
     * Like php array_key_exists, this instead search if (one or more) keys exists in the array
     *
     * @param array $needles - keys to look for in the array
     * @param bool $all - [Optional] if false then checks if at least one key is found
     * @return bool true if the needle(s) is found else false
     */
    public function keysExists(array $needles, bool $all = true): bool
    {
        $size = arrayed($needles)->count();
        $intersect = $this->keys()->intersect($needles);

        return $all
            ? ($intersect->count() === $size)
            : (!$intersect->empty());
    }

    public function head(bool $preserveKeys = true): ArrayedInterface
    {
        return $this->slice(0, 1, $preserveKeys);
    }

    /**
     * Forward the calls to `array_*` that is not yet implemented
     * <br>
     * Assumption is for those array method that accepts the initial array as the first value
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws ArrayedException
     */
    public function __call($name, $arguments)
    {
        // See: https://stackoverflow.com/a/57833019/5704410
        $methodName = strtolower(preg_replace("/([a-z])([A-Z])/", "$1_$2", $name));
        $methodName = 'array_' . $methodName;

        if (function_exists($methodName)) {
            $result = $methodName($this->getWorkableItem(), ...$arguments);

            return is_array($result)
                ? $this->setResult($result)
                : $result;
        }

        throw new ArrayedException(sprintf('Method %s cannot be resolved', $name));
    }
}
