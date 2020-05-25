<?php declare(strict_types=1);

namespace Sunkan\Enum;

class EnumSet implements \Countable
{
    private $value;
    private $set = [];
    private $enumClass;

    public function __construct(?string $enumClass = null)
    {
        if ($enumClass === null) {
            return;
        }

        if (!(class_exists($enumClass) || interface_exists($enumClass))) {
            throw new \InvalidArgumentException("$enumClass not found");
        }

        if (!\in_array(EnumInterface::class, class_implements($enumClass, false), true)) {
            throw new \InvalidArgumentException("$enumClass must implement EnumInterface");
        }

        $this->enumClass = $enumClass;
    }

    public static function fromValue($value, string $enumClass = null, bool $silent = false)
    {
        $set = new static($enumClass);
        $types = explode(',', $value);
        foreach ($types as $type) {
            try {
                /** @var EnumInterface $enumClass */
                $set->attach($enumClass::fromValue($type));
            }
            catch (\UnexpectedValueException $e) {
                if (!$silent) {
                    throw $e;
                }
            }
        }

        return $set;
    }

    public function attachValue($value): void
    {
        if (!$this->enumClass) {
            throw new \BadMethodCallException('Set not fully initialized. Need to specify $enumClass');
        }

        /** @var EnumInterface $enumClass */
        $enumClass = $this->enumClass;
        $this->attach($enumClass::fromValue($value));
    }

    public function attach(EnumInterface $enum): void
    {
        if (!$this->enumClass) {
            $this->enumClass = \get_class($enum);
        }

        if (!$enum instanceof $this->enumClass) {
            throw new \UnexpectedValueException("Value '$enum' is not part of the enum set " . static::class);
        }

        foreach ($this->set as $set) {
            if ($enum->is($set)) {
                return;
            }
        }
        $this->set[] = $enum;

        $this->value = implode(',', $this->set);
    }

    public function detach(EnumInterface $enum): void
    {
        $removeIndex = -1;
        foreach ($this->set as $index => $set) {
            if ($enum->is($set)) {
                $removeIndex = $index;
                break;
            }
        }

        if ($removeIndex !== -1) {
            unset($this->set[$removeIndex]);
        }

        $this->value = implode(',', $this->set);
    }

    public function have(EnumInterface $enum): bool
    {
        foreach ($this->set as $set) {
            if ($enum->is($set)) {
                return true;
            }
        }
        return false;
    }

    public function count(): int
    {
        return \count($this->set);
    }
}
