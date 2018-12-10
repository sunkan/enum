<?php

namespace Sunkan\Enum;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 * @author Daniel Costa <danielcosta@gmail.com>
 * @author Mirosław Filip <mirfilip@gmail.com>
 */
class EnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * getValue()
     */
    public function testGetValue()
    {
        $value = EnumFixture::fromValue(EnumFixture::BAR);
        $this->assertEquals(EnumFixture::BAR, $value->getValue());
    }

    /**
     * getKey()
     */
    public function testGetKey()
    {
        $value = EnumFixture::FOO();
        $this->assertEquals('FOO', $value->getKey());
        $this->assertNotEquals('BA', $value->getKey());
    }

    /**
     * @dataProvider invalidValueProvider
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage is not part of the enum Sunkan\Enum\EnumFixture
     */
    public function testCreatingEnumWithInvalidValue($value)
    {
        EnumFixture::fromValue($value);
    }

    /**
     * Contains values not existing in EnumFixture
     * @return array
     */
    public function invalidValueProvider()
    {
        return array(
            "string" => array('test'),
            "int" => array(1234),
        );
    }

    /**
     * __toString()
     * @dataProvider toStringProvider
     */
    public function testToString($expected, $enumObject)
    {
        $this->assertSame($expected, (string) $enumObject);
    }

    public function toStringProvider()
    {
        return array(
            array(EnumFixture::FOO, EnumFixture::FOO()),
            array(EnumFixture::BAR, EnumFixture::BAR()),
            array((string) EnumFixture::NUMBER, EnumFixture::NUMBER()),
        );
    }

    /**
     * keys()
     */
    public function testKeys()
    {
        $values = EnumFixture::keys();
        $expectedValues = array(
            "FOO",
            "BAR",
            "NUMBER",
            "PROBLEMATIC_NUMBER",
            "PROBLEMATIC_NULL",
            "PROBLEMATIC_EMPTY_STRING",
            "PROBLEMATIC_BOOLEAN_FALSE",
        );

        $this->assertSame($expectedValues, $values);
    }

    /**
     * values()
     */
    public function testValues()
    {
        $values = EnumFixture::values();
        $expectedValues = array(
            "FOO" => EnumFixture::FOO(),
            "BAR" => EnumFixture::BAR(),
            "NUMBER" => EnumFixture::NUMBER(),
            "PROBLEMATIC_NUMBER" => EnumFixture::PROBLEMATIC_NUMBER(),
            "PROBLEMATIC_NULL" => EnumFixture::PROBLEMATIC_NULL(),
            "PROBLEMATIC_EMPTY_STRING" => EnumFixture::PROBLEMATIC_EMPTY_STRING(),
            "PROBLEMATIC_BOOLEAN_FALSE" => EnumFixture::PROBLEMATIC_BOOLEAN_FALSE(),
        );

        $this->assertEquals($expectedValues, $values);
    }

    /**
     * toArray()
     */
    public function testToArray()
    {
        $values = EnumFixture::toArray();
        $expectedValues = array(
            "FOO" => EnumFixture::FOO,
            "BAR" => EnumFixture::BAR,
            "NUMBER" => EnumFixture::NUMBER,
            "PROBLEMATIC_NUMBER" => EnumFixture::PROBLEMATIC_NUMBER,
            "PROBLEMATIC_NULL" => EnumFixture::PROBLEMATIC_NULL,
            "PROBLEMATIC_EMPTY_STRING" => EnumFixture::PROBLEMATIC_EMPTY_STRING,
            "PROBLEMATIC_BOOLEAN_FALSE" => EnumFixture::PROBLEMATIC_BOOLEAN_FALSE,
        );

        $this->assertSame($expectedValues, $values);
    }

    /**
     * __callStatic()
     */
    public function testStaticAccess()
    {
        $this->assertSame(EnumFixture::FOO(), EnumFixture::FOO());
        $this->assertSame(EnumFixture::BAR(), EnumFixture::BAR());
        $this->assertSame(EnumFixture::NUMBER(), EnumFixture::NUMBER());
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage No static method or enum constant 'UNKNOWN' in class
     *                           UnitTest\Sunkan\Enum\Enum\EnumFixture
     */
    public function testBadStaticAccess()
    {
        EnumFixture::UNKNOWN();
    }

    /**
     * isValid()
     * @dataProvider isValidProvider
     */
    public function testIsValid($value, $isValid)
    {
        $this->assertSame($isValid, EnumFixture::isValid($value));
    }

    public function isValidProvider()
    {
        return [
            /**
             * Valid values
             */
            ['foo', true],
            [42, true],
            [null, true],
            [0, true],
            ['', true],
            [false, true],
            /**
             * Invalid values
             */
            ['baz', false]
        ];
    }

    /**
     * isValidKey()
     */
    public function testIsValidKey()
    {
        $this->assertTrue(EnumFixture::isValidKey('FOO'));
        $this->assertFalse(EnumFixture::isValidKey('BAZ'));
        $this->assertTrue(EnumFixture::isValidKey('PROBLEMATIC_NULL'));
    }

    /**
     * search()
     * @see https://github.com/myclabs/php-enum/issues/13
     * @dataProvider searchProvider
     */
    public function testSearch($value, $expected)
    {
        $this->assertSame($expected, EnumFixture::search($value));
    }

    public function searchProvider()
    {
        return array(
            array('foo', 'FOO'),
            array(0, 'PROBLEMATIC_NUMBER'),
            array(null, 'PROBLEMATIC_NULL'),
            array('', 'PROBLEMATIC_EMPTY_STRING'),
            array(false, 'PROBLEMATIC_BOOLEAN_FALSE'),
            array('bar I do not exist', false),
            array(array(), false),
        );
    }

    /**
     * equals()
     */
    public function testEquals()
    {
        $foo = EnumFixture::fromValue(EnumFixture::FOO);
        $number = EnumFixture::fromValue(EnumFixture::NUMBER);
        $anotherFoo = EnumFixture::fromValue(EnumFixture::FOO);

        $this->assertTrue($foo->is($foo));
        $this->assertFalse($foo->is($number));
        $this->assertTrue($foo->is($anotherFoo));
    }

    /**
     * equals()
     */
    public function testEqualsComparesProblematicValuesProperly()
    {
        $false = EnumFixture::fromValue(EnumFixture::PROBLEMATIC_BOOLEAN_FALSE);
        $emptyString = EnumFixture::fromValue(EnumFixture::PROBLEMATIC_EMPTY_STRING);
        $null = EnumFixture::fromValue(EnumFixture::PROBLEMATIC_NULL);

        $this->assertTrue($false->is($false));
        $this->assertFalse($false->is($emptyString));
        $this->assertFalse($emptyString->is($null));
        $this->assertFalse($null->is($false));
    }

    /**
     * equals()
     */
    public function testEqualsConflictValues()
    {
        $this->assertFalse(EnumFixture::FOO()->is(EnumConflict::FOO()));
        $this->assertFalse(EnumFixture::FOO() === EnumConflict::FOO());
    }

    /**
     * jsonSerialize()
     */
    public function testJsonSerialize()
    {
        $this->assertJsonEqualsJson('"foo"', json_encode(EnumFixture::FOO()));
        $this->assertJsonEqualsJson('"bar"', json_encode(EnumFixture::BAR()));
        $this->assertJsonEqualsJson('42', json_encode(EnumFixture::NUMBER()));
        $this->assertJsonEqualsJson('0', json_encode(EnumFixture::PROBLEMATIC_NUMBER()));
        $this->assertJsonEqualsJson('null', json_encode(EnumFixture::PROBLEMATIC_NULL()));
        $this->assertJsonEqualsJson('""', json_encode(EnumFixture::PROBLEMATIC_EMPTY_STRING()));
        $this->assertJsonEqualsJson('false', json_encode(EnumFixture::PROBLEMATIC_BOOLEAN_FALSE()));
    }

    public function testNullableEnum()
    {
        $this->assertNull(EnumFixture::PROBLEMATIC_NULL()->getValue());
        $this->assertNull(EnumFixture::fromValue(EnumFixture::PROBLEMATIC_NULL)->getValue());
        $this->assertNull(EnumFixture::fromValue(EnumFixture::PROBLEMATIC_NULL)->jsonSerialize());
    }

    public function testBooleanEnum()
    {
        $this->assertFalse(EnumFixture::PROBLEMATIC_BOOLEAN_FALSE()->getValue());
        $this->assertFalse(EnumFixture::fromValue(EnumFixture::PROBLEMATIC_BOOLEAN_FALSE)->jsonSerialize());
    }

    private function assertJsonEqualsJson($json1, $json2)
    {
        $this->assertJsonStringEqualsJsonString($json1, $json2);
    }
}