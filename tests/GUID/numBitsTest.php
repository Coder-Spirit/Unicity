<?php
declare(strict_types=1);

namespace Unicity\Tests\GUID;

use PHPUnit\Framework\TestCase;
use Unicity\GUID;

/**
 * @covers \Unicity\GUID::numBits
 */
class numBitsTest extends TestCase
{
    /**
     * @param string $hexString
     * @param int $numBits
     *
     * @dataProvider hexStringsProvider
     */
    public function testNumBitsFromHexString(string $hexString, int $numBits)
    {
        $this->assertEquals($numBits, GUID::fromHexString($hexString, $numBits / 8)->numBits());
    }

    /**
     * @param string $b64String
     * @param int $numBits
     *
     * @dataProvider b64StringsProvider
     */
    public function testNumBitsFromBase64String(string $b64String, int $numBits)
    {
        $this->assertEquals($numBits, GUID::fromBase64String($b64String, $numBits / 8)->numBits());
    }

    public function hexStringsProvider(): array
    {
        return [
            ['0123456789ab', 48],
            ['0123456789abcd', 56],
            ['0123456789abcdef', 64],
            ['0123456789abcdef01', 72],
            ['0123456789abcdef0123', 80],
            ['0123456789abcdef012345', 88],
            ['0123456789abcdef01234567', 96],
            ['0123456789abcdef0123456789', 104],
            ['0123456789abcdef0123456789ab', 112],
            ['0123456789abcdef0123456789abcd', 120],
            ['0123456789abcdef0123456789abcdef', 128]
        ];
    }

    public function b64StringsProvider(): array
    {
        return [
            ["ASNFZ4mr", 48],
            ["ASNFZ4mrzQ==", 56],
            ["ASNFZ4mrze8=", 64],
            ["ASNFZ4mrze8B", 72],
            ["ASNFZ4mrze8BIw==", 80],
            ["ASNFZ4mrze8BI0U=", 88],
            ["ASNFZ4mrze8BI0Vn", 96],
            ["ASNFZ4mrze8BI0VniQ==", 104],
            ["ASNFZ4mrze8BI0Vnias=", 112],
            ["ASNFZ4mrze8BI0VniavN", 120],
            ["ASNFZ4mrze8BI0VniavN7w==", 128]
        ];
    }
}