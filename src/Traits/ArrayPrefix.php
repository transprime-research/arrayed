<?php

declare(strict_types=1);

namespace Transprime\Arrayed\Traits;

use Transprime\Arrayed\Exceptions\ArrayedException;
use Transprime\Arrayed\Interfaces\ArrayedInterface;
use Transprime\Arrayed\Types\Undefined;

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

    public function walk(callable $callable, $arg = null)
    {
        $workableItem = $this->getWorkableItem();
        array_walk($workableItem, function (&$value, $key, $arg) use ($callable) {
            $value = $callable($value, $key, $arg);
        }, $arg);

        return $this->setResult($workableItem);
    }


    public function walkRecursive(callable $callable, $arg = null)
    {
        $workableItem = $this->getWorkableItem();
        array_walk_recursive($workableItem, function (&$value, $key, $arg) use ($callable) {
            $value = $callable($value, $key, $arg);
        }, $arg);

        return $this->setResult($workableItem);
    }

    public function search($needle, bool $strict = true, $default = null)
    {
        if ($needle instanceof \Closure) {
            return $this->filter(fn($value, $key) => $needle($value, $key))
                ->keys()
                ->when($this->getWorkableItem(), new self([$default]))
                ->offsetGet(0);
        }


        $result = array_search($needle, $this->getWorkableItem(), $strict);

        if ($result === false) {
            return $default;
        }

        return $result;
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

    /**
     * @return ArrayedInterface|mixed
     */
    public function head(bool $preserveKeys = false)
    {
        return self::makeArrayed(
            $this->when($this->getWorkableItem())
                ->slice(0, 1, $preserveKeys)
                ->values()
                ->offsetGet(0)
        );
    }

    public function tail(): ArrayedInterface
    {
        return $this->when($this->getWorkableItem())
            ->slice(1);
    }

    private function when($truthyValue, $default = Undefined::class)
    {
        if ($truthyValue) {
            return $this;
        }

        if ($default === Undefined::class || $default instanceof Undefined) {
            throw new \InvalidArgumentException('Value cannot be resolved');
        }

        return $this->setResult($default);
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
