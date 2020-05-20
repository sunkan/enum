<?php declare(strict_types=1);

namespace Sunkan\Enum;

use PHPUnit\Framework\TestCase;

class EnumSetTest extends TestCase
{
    public function testCreateWithNonEnum(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new EnumSet('test');
    }

    public function testCreateEmptySet(): void
    {
        $set = new EnumSet(EnumFixture::class);
        $this->assertCount(0, $set);

        $set->attach(EnumFixture::BAR());
        $this->assertCount(1, $set);
    }

    public function testAttachInvalidType(): void
    {
        $set = new EnumSet(EnumFixture::class);
        $this->expectException(\UnexpectedValueException::class);
        $set->attach(EnumConflict::FOO());
    }

    public function testCreateSetWithoutDefaultType(): void
    {
        $set = new EnumSet();
        $this->assertCount(0, $set);

        $set->attach(EnumFixture::BAR());
        $this->assertCount(1, $set);

        $this->expectException(\UnexpectedValueException::class);
        $set->attach(EnumConflict::FOO());
    }

    public function testCreateFromValue(): void
    {
        $set = EnumSet::fromValue('bar,foo', EnumFixture::class);

        $this->assertCount(2, $set);
    }

    public function testCreateFromValueWithInvalidValue(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        EnumSet::fromValue('bar,foo-error', EnumFixture::class);
    }

    public function testCreateFromValueWithInvalidValueSilent(): void
    {
        $set = EnumSet::fromValue('bar,foo-error', EnumFixture::class, true);
        $this->assertCount(1, $set);
    }

    public function testAttacheValue(): void
    {
        $set = new EnumSet(EnumFixture::class);
        $this->assertCount(0, $set);

        $set->attachValue(EnumFixture::BAR);
        $this->assertCount(1, $set);

        $this->assertTrue($set->have(EnumFixture::BAR()));
    }

    public function testAttacheValueWithOutType(): void
    {
        $set = new EnumSet();
        $this->assertCount(0, $set);

        $this->expectException(\BadMethodCallException::class);
        $set->attachValue(EnumFixture::BAR);
    }

    public function testDetach(): void
    {
        $set = EnumSet::fromValue('foo,bar', EnumFixture::class);
        $this->assertCount(2, $set);
        $this->assertTrue($set->have(EnumFixture::FOO()));

        $set->detach(EnumFixture::FOO());
        $this->assertCount(1, $set);

        $this->assertFalse($set->have(EnumFixture::FOO()));
    }

    public function testAttacheDuplicateValue(): void
    {
        $set = new EnumSet(EnumFixture::class);
        $this->assertCount(0, $set);

        $set->attachValue(EnumFixture::BAR);
        $this->assertCount(1, $set);

        $set->attach(EnumFixture::BAR());
        $this->assertCount(1, $set);

        $this->assertTrue($set->have(EnumFixture::BAR()));
    }

    public function testAttacheCustomEnum(): void
    {
        $set = new EnumSet(CustomEnum::class);
        $this->assertCount(0, $set);

        $enum = CustomEnum::fromValue('value');
        $set->attach($enum);
        $this->assertCount(1, $set);

        $this->assertTrue($set->have($enum));
    }
}
