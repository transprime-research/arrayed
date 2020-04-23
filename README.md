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

```php
//
```

## Coming Soon

-

> Api implementation to be decided

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