<p align="center">
<img src="https://github.com/transprime-research/assets/blob/master/arrayed/twitter_header_photo_2.png">
</p>

<p align="center">
<a href="https://travis-ci.com/transprime-research/arrayed"> <img src="https://travis-ci.org/transprime-research/arrayed.svg?branch=master" alt="Build Status"/></a>
<a href="https://packagist.org/packages/transprime-research/arrayed"> <img src="https://poser.pugx.org/transprime-research/arrayed/v/stable" alt="Latest Stable Version"/></a>
<a href="https://packagist.org/packages/transprime-research/arrayed"> <img src="https://poser.pugx.org/transprime-research/arrayed/downloads" alt="Total Downloads"/></a>
<a href="https://packagist.org/packages/transprime-research/arrayed"> <img src="https://poser.pugx.org/transprime-research/arrayed/v/unstable" alt="Latest Unstable Version"/></a>
<a href="https://packagist.org/packages/transprime-research/arrayed"> <img src="https://poser.pugx.org/transprime-research/arrayed/d/monthly" alt="Latest Monthly Downloads"/></a>
  <a href="https://packagist.org/packages/transprime-research/arrayed"> <img src="https://poser.pugx.org/transprime-research/arrayed/license" alt="License"/></a>
</p>

## About Arrayed

Simple PHP Array(ed) in object oriented way wrapping [PHP Arrays](https://www.php.net/manual/en/ref.array.php) in a consistent manner.
> No advanced stuff, just wrap PHP array_* functions and a little more. Do it Like a PRO :ok:

> Looking for PHP Array on Steroid? See: https://laravel.com/docs/collections

## Installation

```shell script
composer require transprime-research/arrayed
```

## Requirement
Minimum Requirement
- PHP 7.2 +
- Composer

## Quick Usage

```php
arrayed(1, 2, 'ninja')
    ->filter(fn($val) => is_int($val))
    ->map(fn($val) => $val + 1)
    ->flip()
    ->values()
    ->sum()(); //use () or ->result() at the end;
```

Instead of:

```php
$result = array_filter([1, 2, 'ninja'], fn($val) => is_int($val));
$result = array_map(fn($val) => $val + 1, $result);
$result = array_flip($result);
$result = array_values($result);
$result = array_sum($result);
```
> PS: You can still use the old `function() { return v; }`, `fn()` is the new short arrow function in PHP 7.4+ See: https://www.php.net/manual/en/functions.arrow.php

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

Initial values can be passed in two ways:

```php
//Non associative
arrayed(1, 2);

//OR
arrayed([1, 2]);

// For associative array, only this way
arrayed(['a' => 1, 'b' => 2]); 
```

#### With Laravel & Laravel Collection

Laravel Collections

```php
collect(arrayed(1, 2, 3, 4));

// Or
new Collection(arrayed(1, 2, 3, 4));

// Or
Collection::make(arrayed(1, 2, 3, 4));
```

Laravel Response accepts `Arrayed`:

```php
response()->json(arrayed(1, 2, 3)->flip());
```

## Others

If any operation normally returns an array, the return value will give `Arrayed` instance so that other methods can be chained on them otherwise a non-array value is returned as can be seen that `sum()` returns an integer in the example below:

Example:

```php
arrayed(['a' => 1, 'b' => 2])
    ->values() // returns array, we can chain
    ->sum(); // returns an integer, we cannot chain
```

You can still work on the result (if its an array'ed value) by passing a closure/callable function to `result()` method:

```php
arrayed(['a' => 'name', 'b' => 'age'])
    ->values()
    ->result(fn($val) => implode(',', $val)); //'name,age'

//Or

arrayed(['a' => 'name', 'b' => 'age'])
    ->values()(fn($val) => implode(',', $val)); //'name,age'
```

Get the original array data with `raw()` method

```php
arrayed([1, 2])->raw(); //[1,2]
```

As at now not all `array_*` functions have been implemented.
`pipe()` method helps to call custom function on the array result.

Such as `array_unique` used in this way:  

```php
arrayed(['a' => 'www', 'b' => 'dot', 'c' => 'www'])
    ->pipe('array_unique') // data is piped forward to `array_unique`
    ->flip()
    ->values()(); //['a', 'b']
```
> The pipe method makes use of [Piper](https://github.com/transprime-research/piper) - A PHP functional pipe'ing
> See `\Transprime\Arrayed\Tests\ArrayedTest` 

## Coming Soon

- Implement other `array_*` methods

> Api implementation to be decided

## APIs

These are the API's available

```php
static Arrayed::on(...$values): ArrayedInterface;

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

Arrayed::pipe(callable $action, ...$parameters);

Arrayed::result(callable $callable = null);

Arrayed::raw(): array;

Arrayed::initial(): array; // Deprecated, use raw() instead

Arrayed::__toString(): string; // returns string rep of the array
```

## Additional Information

Be aware that this package is part of a series of "The Proof of Concept".

See other packages in this series here:

- https://github.com/transprime-research/piper [A functional PHP pipe in object-oriented way]
- https://github.com/omitobi/conditional [A smart PHP if...elseif...else statement]
- https://github.com/transprime-research/attempt [A smart PHP try...catch statement]
- https://github.com/omitobi/corbonate [A smart Carbon + Collection package]
- https://github.com/omitobi/laravel-habitue [Jsonable Http Request(er) package with Collections response]

## Similar packages

- https://github.com/bocharsky-bw/Arrayzy - Identical but with more actions and features
- https://github.com/voku/Arrayy - Perform more than just an OOP style on Arrays
- https://github.com/dantodev/php-array-tools - array tools plus collections
- https://github.com/minwork/array - Pack of advanced PHP array functions
- https://github.com/mblarsen/arrgh - A Sane PHP Array library with advance functions
- More at: https://www.google.com/search?q=php+array+github

## Licence

MIT (See LICENCE file)
