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
     * @param string $invalidHexStr
     *
     * @dataProvider invalidHexStringsProvider
     * @expectedException \Unicity\Errors\UnserializationError
     * @expectedExceptionMessage Invalid hexadecimal string
     */
    public function test_invalid_hex_strings(string $invalidHexStr)
    {
        GUID::fromHexString($invalidHexStr, \strlen($invalidHexStr)/2);
    }

    public function invalidHexStringsProvider()
    {
        return [
            ['xyxyxyxyxyxy'],
            ['abcdefABCDEF'],
            ['0123456789a-']
        ];
    }
}
