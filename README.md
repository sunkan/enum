# PHP Enum implementation

## Installation

```
composer require sunkan/enum
```

## Declaration

```php
use Sunkan\Enum\Enum;

/**
 * Action enum
 */
class Action extends Enum
{
    private const VIEW = 'view';
    private const EDIT = 'edit';
}
```

## Usage

```php
$action = Action::fromValue('view');

// or
$action = Action::VIEW();
```

As you can see, static methods are automatically implemented to provide quick access to an enum value.

One advantage over using class constants is to be able to type-hint enum values:

```php
function setAction(Action $action) {
    // ...
}
```


## Documentation

- `__toString()` You can `echo $myValue`, it will display the enum value (value of the constant)
- `getValue()` Returns the current value of the enum
- `getKey()` Returns the key of the current value on Enum
- `is()` Tests whether enum instances are equal (returns `true` if enum values are equal, `false` otherwise)

Static methods:

- `fromValue()` The named constructor checks that the value exist in the enum
- `toArray()` method Returns all possible values as an array (constant name in key, constant value in value)
- `keys()` Returns the names (keys) of all constants in the Enum class
- `values()` Returns instances of the Enum class of all Enum constants (constant name in key, Enum instance in value)
- `isValid()` Check if tested value is valid on enum set
- `isValidKey()` Check if tested key is valid on enum set
- `search()` Return key for searched value

