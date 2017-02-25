<?php
declare(strict_types=1);

namespace Unicity\Tests\GUID;

use PHPUnit\Framework\TestCase;
use Unicity\GUID;
use Unicity\Interfaces\GUID as GUIDInterface;

/**
 * @covers \Unicity\GUID::__construct
 * @covers \Unicity\GUID::fromBinaryString
 */
class fromBinaryStringTest extends TestCase
{
    /**
     * @expectedException \Unicity\Errors\GUIDInvariantsViolationError
     * @expectedExceptionMessage IDs must have at least 6 bytes of entropy
     */
    public function test_too_small_expected_length()
    {
        GUID::fromBinaryString('0000', 4);
    }

    /**
     * @expectedException \Unicity\Errors\UnserializationError
     * @expectedExceptionMessage The passed string has an unexpected length {"expected":6,"given":4}
     */
    public function test_unexpected_length()
    {
        GUID::fromBinaryString('0000', 6);
    }

    /**
     * @param string $binaryStr
     * @param int $expectedLength
     *
     * @dataProvider validParamsProvider
     */
    public function test_valid_params(string $binaryStr, int $expectedLength)
    {
        $guid = GUID::fromBinaryString($binaryStr, $expectedLength);
        $this->assertInstanceOf(GUIDInterface::class, $guid);
        $this->assertInstanceOf(GUID::class, $guid);
    }

    public function validParamsProvider()
    {
        return [
            ['123456', 6],
            ['12345678', 8],
            ['1234567890', 10],
            ['1234567890ab', 12],
            ['1234567890abcd', 14],
            ['1234567890abcdef', 16]
        ];
    }
}
