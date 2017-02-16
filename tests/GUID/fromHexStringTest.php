<?php
declare(strict_types=1);

namespace Unicity\Tests\GUID;

use PHPUnit\Framework\TestCase;
use Unicity\GUID;

/**
 * @covers \Unicity\GUID::__construct
 * @covers \Unicity\GUID::fromHexString
 */
class fromHexStringTestTest extends TestCase
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
