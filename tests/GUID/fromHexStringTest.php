<?php
declare(strict_types=1);

namespace Unicity\Tests\GUID;

use PHPUnit\Framework\TestCase;
use Unicity\Interfaces\GUID as GUIDInterface;
use Unicity\GUID;

/**
 * @covers \Unicity\GUID::__construct
 * @covers \Unicity\GUID::fromHexString
 */
class fromHexStringTest extends TestCase
{
    /**
     * @expectedException \Unicity\Errors\GUIDInvariantsViolationError
     * @expectedExceptionMessage IDs must have at least 6 bytes of entropy
     */
    public function test_too_small_expected_length()
    {
        GUID::fromHexString('abcd', 4);
    }

    /**
     * @expectedException \Unicity\Errors\UnserializationError
     * @expectedExceptionMessage The passed string has an unexpected length {"expected":6,"given":4}
     */
    public function test_unexpected_length()
    {
        GUID::fromHexString('abcdef01', 6);
    }

    /**
     * @param string $hexStr
     * @param int $expectedLength
     *
     * @dataProvider validParamsProvider
     */
    public function test_valid_params(string $hexStr, int $expectedLength)
    {
        $guid = GUID::fromHexString($hexStr, $expectedLength);
        $this->assertInstanceOf(GUIDInterface::class, $guid);
        $this->assertInstanceOf(GUID::class, $guid);
    }

    public function validParamsProvider()
    {
        return [
            ['123456123456', 6],
            ['1234567812345678', 8],
            ['12345678901234567890', 10],
            ['1234567890ab1234567890ab', 12],
            ['1234567890abcd1234567890abcd', 14],
            ['1234567890abcdef1234567890abcdef', 16]
        ];
    }

    /**
     * @param string $invalidHexStr
     *
     * @dataProvider invalidHexStringsProvider
     * @expectedException \Unicity\Errors\UnserializationError
     * @expectedExceptionMessage Invalid hexadecimal string
     */
    public function test_invalid_hex_strings(string $invalidHexStr)
    {
        GUID::fromHexString($invalidHexStr, (int)(\strlen($invalidHexStr)/2));
    }

    public function invalidHexStringsProvider()
    {
        return [
            ['xyxyxyxyxyxy'],
            ['abcdefABCDEF'],
            ['0123456789a-'],
            ['0123456789abc']
        ];
    }
}
