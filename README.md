# Arrayed

PHP Array(ed) in object oriented way wrapping [PHP Arrays](https://www.php.net/manual/en/ref.array.php) in a consistent manner.
> Do it Like a PRO :ok:

> Looking for PHP Array on Steroid? See: https://laravel.com/docs/collections

## Installation

- `composer require transprime-research/arrayed`

## Quick Usage

```php
arrayed(1,2, 'ninja')
    ->filter(fn($val) => is_int($val))
    ->map(fn($val) => $val + 1)
    ->flip()
    ->values()
    ->sum()(); //or ->sum()->result();
```

## Other Usages

Arrayed can be instantiated in 3 ways:

```php
use Transprime\Arrayed\Arrayed;

// Nifty
arrayed(1, 2)->count();

// Easier
Arrayed::on(1, 2)->count();

// Normal with (new instance)
(new Arrayed(1,2))->count();
```

As at now not all `array_*` functions have been implemented.
`pipe()` method helps to call custom function on the array result.

Such as `array_unique` used in this way:  

```php
arrayed(['a' => 'www', 'b' => 'dot', 'c' => 'www'])
    ->pipe('array_unique') // data is piped forward to `array_unique`
    ->flip()
    ->values()(); //returns ['a', 'b']
```
> The pipe method makes use of [Piper](https://github.com/transprime-research/piper) - A PHP functional pipe'ing

## Coming Soon

- Implement other `array_*` methods
- Integrate with [Laravel Collections](https://laravel.com/docs/collections) i.e `collect(arrayed(1, 2, 3))->sum()`

> Api implementation to be decided

## APIs

These are the API's available

```php
Arrayed::map($callback): ArrayedInterface;

Arrayed::filter($callback = null, int $flag = 0): ArrayedInterface;

Arrayed::reduce($function, $initial = null): ArrayedInterface;

Arrayed::merge(array $array2 = null, ...$_): ArrayedInterface;

Arrayed::mergeRecursive(...$_): ArrayedInterface;

Arrayed::flip(): ArrayedInterface;

Arrayed::intersect(array $array2, ...$_): ArrayedInterface;

Arrayed::values(): ArrayedInterface;

Arrayed::keys($overwrite = true): ArrayedInterface;

Arrayed::offsetGet($offset);

Arrayed::offsetSet($offset, $value): ArrayedInterface;

Arrayed::offsetUnset($offset): ArrayedInterface;

Arrayed::sum(): int;

Arrayed::contains($needle, bool $strict = false): bool;

Arrayed::isArray(): bool;

Arrayed::keyExists($key): bool;

Arrayed::offsetExists($offset): bool;

Arrayed::empty(): bool;

Arrayed::count(): int;

Arrayed::getIterator();

Arrayed::pipe(callable $action, ...$parameters);

Arrayed::result(callable $callable = null);
```

## Additional Information

Be aware that this package is part of a series of "The Proof of Concept" so best practices might not be the top priority.

See other packages in this series here:

- https://github.com/transprime-research/piper [A functional PHP pipe in object-oriented way]
- https://github.com/omitobi/conditional [A smart PHP if...elseif...else statement]
- https://github.com/transprime-research/attempt [A smart PHP try...catch statement]
- https://github.com/omitobi/corbonate [A smart Carbon + Collection package]
- https://github.com/omitobi/laravel-habitue [Jsonable Http Request(er) package with Collections response]
- https://github.com/transprime-research/arrayer [Array now an object]

## Similar packages


## Licence

MIT (See LICENCE file)