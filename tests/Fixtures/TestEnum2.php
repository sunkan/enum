<?php declare(strict_types=1);

namespace Sunkan\Enum\Fixtures;

use Sunkan\Enum\Enum;
use Sunkan\Enum\EnumInterface;

/**
 * @method static ADMIN()
 */
final class TestEnum2 extends Enum implements TestEnumInterface
{
    public const TEST_2 = 'test2';
}
