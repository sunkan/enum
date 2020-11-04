<?php declare(strict_types=1);

namespace Sunkan\Enum\Fixtures;

use Sunkan\Enum\Enum;

/**
 * @method static ADMIN()
 */
final class TestEnum1 extends Enum implements TestEnumInterface
{
    public const TEST_1 = 'test1';
}
