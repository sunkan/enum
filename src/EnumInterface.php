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
interface EnumInterface
{
    /**
     * @return static
     */
    public static function fromValue($value);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * Returns the enum key (i.e. the constant name).
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Compares one Enum with another.
     *
     * This method is final, for more information read https://github.com/myclabs/php-enum/issues/4
     *
     * @param EnumInterface $enum
     * @return bool True if Enums are equal, false if not equal
     */
    public function is(EnumInterface $enum): bool;

    public function __toString();
}