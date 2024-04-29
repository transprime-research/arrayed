<?php

declare(strict_types=1);

namespace Transprime\Arrayed;

use Closure;
use ArrayIterator;
use Transprime\Arrayed\Exceptions\ArrayedException;
use Transprime\Arrayed\Types\Undefined;
use Transprime\Arrayed\Traits\ArrayPrefix;
use Transprime\Arrayed\Interfaces\ArrayedInterface;

class Arrayed implements ArrayedInterface
{
    use ArrayPrefix;

    private array $raw;

    private $result;

    public function __construct(...$values)
    {
        $this->raw = $this->argumentsToArray(...$values);

        $this->setResult(new Undefined());
    }

    private function argumentsToArray(...$values): array
    {
        if (func_num_args() === 1 && is_array($values[0])) {
            return $values[0];
        }

        if (func_num_args() === 1 && $values[0] instanceof ArrayedInterface) {
            return $values[0]->toArray();
        }

        return $values;
    }

    public static function on(...$values): ArrayedInterface
    {
        return new static($values);
    }

    public function __invoke(callable $callable = null)
    {
        return $this->result($callable);
    }

    public function map($callback, ...$_): ArrayedInterface
    {
        return $this->setResult(array_map($callback, $this->getWorkableItem(), ...$_));
    }

    public function filter($callback = null, int $flag = 0): ArrayedInterface
    {
        if ($callback) {
            return $this->setResult(array_filter($this->getWorkableItem(), $callback, $flag));
        }

        return $this->setResult(array_filter($this->getWorkableItem()));
    }

    public function reduce($function, $initial = null): ArrayedInterface
    {
        return $this->setResult(array_reduce($this->getWorkableItem(), $function, $initial));
    }

    public function merge(array $array2 = null, ...$_): ArrayedInterface
    {
        return $this->setResult(array_merge($this->getWorkableItem(), $array2, ...$_));
    }

    public function mergeRecursive(...$_): ArrayedInterface
    {
        return $this->setResult(array_merge_recursive($this->getWorkableItem(), ...$_));
    }

    public function flip(): ArrayedInterface
    {
        return $this->setResult(array_flip($this->getWorkableItem()));
    }

    public function intersect(array $array2, ...$_): ArrayedInterface
    {
        return $this->setResult(array_intersect($this->getWorkableItem(), $array2, ...$_));
    }

    public function values(): ArrayedInterface
    {
        return $this->setResult(array_values($this->getWorkableItem()));
    }

    public function keys($overwrite = true): ArrayedInterface
    {
        $keys = array_keys($this->getWorkableItem());

        if (!$overwrite) {
            return $this->makeArrayed($keys);
        }

        return $this->setResult($keys);
    }

    public function offsetGet($offset)
    {
        return $this->makeArrayed($this->getWorkableItem()[$offset]);
    }

    public function offsetSet($offset, $value): ArrayedInterface
    {
        return $this->merge([$offset => $value]);
    }

    public function offsetUnset($offset): ArrayedInterface
    {
        $item = $this->getWorkableItem();

        unset($item[$offset]);

        return $this->setResult($item);
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

    public function pipe(callable $action, ...$parameters)
    {
        return $this->setResult(
            piper($this->getWorkableItem())->to($action, ...$parameters)()
        );
    }

    private function setResult($value)
    {
        $this->result = $value;

        return $this;
    }

    public function result(callable $callable = null)
    {
        return $callable ? $callable($this->result) : $this->getWorkableItem();
    }

    private function getWorkableItem(bool $asArray = false)
    {
        if ($this->result instanceof Undefined) {
            return $this->raw;
        }

        if ($asArray && !is_array($this->result)) {
            return (array)$this->result;
        }

        return $this->result;
    }

    private static function makeArrayed($data)
    {
        return is_array($data) ? new static($data) : $data;
    }

    public function raw(): array
    {
        return $this->raw;
    }

    /**
     * @inheritDoc
     */
    public function initial(): array
    {
        return $this->raw;
    }

    public function __toString(): string
    {
        return json_encode($this->getWorkableItem(true));
    }

    public function toArray(): array
    {
        return $this->walk(
            function ($value) {
                return $value instanceof ArrayedInterface ? $value->getWorkableItem() : $value;
            }
        )->result();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->getWorkableItem(true);
    }

    public function copy(): ArrayedInterface
    {
        return new static($this->result());
    }

    public function tap(Closure $closure): ArrayedInterface
    {
        function_exists('tap') ? tap($this->copy(), $closure) : $closure($this->copy());

        return $this;
    }

    /**
     * @param $with
     * @return mixed
     * @throws ArrayedException
     */
    public function collect(...$with)
    {
        $collectionClass = $this->getConfig('collection_class');

        if ($collectionClass && class_exists($collectionClass)) {
            return new $collectionClass($this->copy()->merge($this->argumentsToArray(...$with))->result());
        }

        throw new ArrayedException('Collection class is not set or does not exist');
    }

    private function getConfig($item)
    {
        return configured("arrayed.$item", null);
    }
}
