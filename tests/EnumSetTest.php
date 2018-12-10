<?php declare(strict_types=1);

namespace Sunkan\Enum;

use PHPUnit\Framework\TestCase;

class EnumSetTest extends TestCase
{
    public function testCreateWithNonEnum()
    {
        $this->expectException(\InvalidArgumentException::class);
        new EnumSet('test');
    }

    public function testCreateEmptySet()
    {
        $set = new EnumSet(EnumFixture::class);
        $this->assertCount(0, $set);

        $set->attach(EnumFixture::BAR());
        $this->assertCount(1, $set);
    }

    public function testAttachInvalidType()
    {
        $set = new EnumSet(EnumFixture::class);
        $this->expectException(\UnexpectedValueException::class);
        $set->attach(EnumConflict::FOO());
    }

    public function testCreateSetWithoutDefaultType()
    {
        $set = new EnumSet();
        $this->assertCount(0, $set);

        $set->attach(EnumFixture::BAR());
        $this->assertCount(1, $set);

        $this->expectException(\UnexpectedValueException::class);
        $set->attach(EnumConflict::FOO());
    }

    public function testCreateFromValue()
    {
        $set = EnumSet::fromValue('bar,foo', EnumFixture::class);

        $this->assertCount(2, $set);
    }

    public function testCreateFromValueWithInvalidValue()
    {
        $this->expectException(\UnexpectedValueException::class);
        EnumSet::fromValue('bar,foo-error', EnumFixture::class);
    }

    public function testCreateFromValueWithInvalidValueSilent()
    {
        $set = EnumSet::fromValue('bar,foo-error', EnumFixture::class, true);
        $this->assertCount(1, $set);
    }

    public function testAttacheValue()
    {
        $set = new EnumSet(EnumFixture::class);
        $this->assertCount(0, $set);

        $set->attachValue(EnumFixture::BAR);
        $this->assertCount(1, $set);

        $this->assertTrue($set->have(EnumFixture::BAR()));
    }

    public function testAttacheValueWithOutType()
    {
        $set = new EnumSet();
        $this->assertCount(0, $set);

        $this->expectException(\BadMethodCallException::class);
        $set->attachValue(EnumFixture::BAR);
    }

    public function testDetach()
    {
        $set = EnumSet::fromValue('foo,bar', EnumFixture::class);
        $this->assertCount(2, $set);
        $this->assertTrue($set->have(EnumFixture::FOO()));

        $set->detach(EnumFixture::FOO());
        $this->assertCount(1, $set);

        $this->assertFalse($set->have(EnumFixture::FOO()));

    }
}
