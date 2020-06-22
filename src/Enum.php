<?php declare(strict_types=1);

namespace Sunkan\Enum;

/**
 * Base Enum class
 *
 * Create an enum by implementing this class and adding class constants.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 * @author Daniel Costa <danielcosta@gmail.com>
 * @author Mirosław Filip <mirfilip@gmail.com>
 * @author Andreas Sundqvist <andreas@oak-valley.se>
 */
abstract class Enum implements \JsonSerializable, EnumInterface
{
    /**
     * Enum value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Store existing constants in a static cache per object.
     *
     * @var array
     */
    protected static $cache = [];

    private static $instances = [];

    /**
     * Creates a new value of some type
     *
     * @param mixed $value
     *
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    private function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public static function fromValue($value)
    {
        if (!self::isValid($value)) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . static::class);
        }

        $valueKey = static::class . '::' . md5(serialize($value));

        if (!isset(self::$instances[$valueKey])) {
            self::$instances[$valueKey] = new static($value);
        }

        return self::$instances[$valueKey];
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function getKey()
    {
        return static::search($this->value);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    public function toString(): string
    {
        return (string)$this->value;
    }

    /**
     * @inheritDoc
     */
    final public function is(EnumInterface $enum): bool
    {
        return $this->getValue() === $enum->getValue() && static::class === \get_class($enum);
    }

    final public function in(EnumInterface ...$enums): bool
    {
        foreach ($enums as $enum) {
            if ($this->is($enum)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the names (keys) of all constants in the Enum class
     *
     * @return array
     */
    public static function keys(): array
    {
        return \array_keys(static::toArray());
    }

    /**
     * Returns instances of the Enum class of all Enum constants
     *
     * @return static[] Constant name in key, Enum instance in value
     */
    public static function values(): array
    {
        $values = array();
        foreach (static::toArray() as $key => $value) {
            $values[$key] = static::fromValue($value);
        }
        return $values;
    }

    /**
     * Returns all possible values as an array
     *
     * @return array Constant name in key, constant value in value
     */
    public static function toArray(): array
    {
        $class = static::class;
        if (!isset(static::$cache[$class])) {
            $reflection = new \ReflectionClass($class);
            static::$cache[$class] = $reflection->getConstants();
        }
        return static::$cache[$class];
    }

    /**
     * Check if is valid enum value
     *
     * @param $value
     * @return bool
     */
    public static function isValid($value): bool
    {
        return \in_array($value, static::toArray(), true);
    }

    /**
     * Check if is valid enum key
     *
     * @param $key
     * @return bool
     */
    public static function isValidKey($key): bool
    {
        $array = static::toArray();
        return isset($array[$key]) || \array_key_exists($key, $array);
    }

    /**
     * Return key for value
     *
     * @param $value
     * @return mixed
     */
    public static function search($value)
    {
        return \array_search($value, static::toArray(), true);
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     *
     * @param string $name
     * @param array $arguments
     * @return static
     * @throws \BadMethodCallException
     */
    public static function __callStatic($name, $arguments)
    {
        $array = static::toArray();
        if (isset($array[$name]) || \array_key_exists($name, $array)) {
            return static::fromValue($array[$name]);
        }
        throw new \BadMethodCallException("No static method or enum constant '$name' in class " . static::class);
    }

    /**
     * Specify data which should be serialized to JSON. This method returns data that can be serialized by json_encode()
     * natively.
     *
     * @return mixed
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }
}
