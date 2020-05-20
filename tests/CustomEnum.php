<?php declare(strict_types=1);

namespace Sunkan\Enum;

final class CustomEnum implements EnumInterface
{
    /** @var string */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromValue($value)
    {
        return new self($value);
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getKey()
    {
        return strtoupper($this->value);
    }

    public function is(EnumInterface $enum): bool
    {
        return $this->getValue() === $enum->getValue() && static::class === \get_class($enum);
    }

    public function __toString()
    {
        return $this->value;
    }
}
