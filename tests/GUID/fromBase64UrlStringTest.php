<?php
declare(strict_types=1);

namespace Unicity\Tests\GUID;

use PHPUnit\Framework\TestCase;
use Unicity\Interfaces\GUID as GUIDInterface;
use Unicity\GUID;

/**
 * @covers \Unicity\GUID::fromBase64UrlString
 * @covers \Unicity\GUID::fromBase64String
 */
class fromBase64UrlStringTest extends TestCase
{
    /**
     * @expectedException \Unicity\Errors\GUIDInvariantsViolationError
     * @expectedExceptionMessage IDs must have at least 6 bytes of entropy
     */
    public function test_too_small_expected_length()
    {
        GUID::fromBase64UrlString('YWJjZA..', 4);
    }

    /**
     * @expectedException \Unicity\Errors\UnserializationError
     * @expectedExceptionMessage The passed string has an unexpected length {"expected":6,"given":8}
     */
    public function test_unexpected_length()
    {
        GUID::fromBase64UrlString('YWJjZGVmMDE.', 6);
    }

    /**
     * @param string $b64Str
     * @param int $expectedLength
     *
     * @dataProvider validParamsProvider
     */
    public function test_valid_params(string $b64Str, int $expectedLength)
    {
        $guid = GUID::fromBase64UrlString($b64Str, $expectedLength);
        $this->assertInstanceOf(GUIDInterface::class, $guid);
        $this->assertInstanceOf(GUID::class, $guid);
    }

    public function validParamsProvider()
    {
        return [
            ['EjRWEjRW', 6],
            ['EjRWeBI0Vng.', 8],
            ['EjRWeJASNFZ4kA..', 10],
            ['EjRWeJCrEjRWeJCr', 12],
            ['EjRWeJCrzRI0VniQq80.', 14],
            ['EjRWeJCrze8SNFZ4kKvN7w..', 16]
        ];
    }

    /**
     * @param string $invalidBase64Str
     *
     * @dataProvider invalidBase64UrlStringsProvider
     * @expectedException \Unicity\Errors\UnserializationError
     * @expectedExceptionMessage Invalid base64 string
     */
    public function test_invalid_base64_strings(string $invalidBase64Str)
    {
        GUID::fromBase64UrlString($invalidBase64Str, 8);
    }

    public function invalidBase64UrlStringsProvider()
    {
        return [
            ['YWJjZGVmMDE'],
            ['YWJjZGVmMDE..'],
            ['YWJj*GVmMDE.'],
            ['YWJjZGVmMDE=']
        ];
    }
}
