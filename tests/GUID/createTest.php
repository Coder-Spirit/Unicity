<?php
declare(strict_types=1);

namespace Unicity\Tests\GUID;

use PHPUnit\Framework\TestCase;
use Unicity\GUID;

/**
 * @covers \Unicity\GUID::__construct
 * @covers \Unicity\GUID::create
 */
class createTest extends TestCase
{
    public function validParamsProvider()
    {
        return [
            [4, 7, 11, 22],
            [5, 7, 12, 24],
            [6, 7, 13, 26],
            [7, 7, 14, 28],
            [4, 8, 12, 24],
            [5, 8, 13, 26],
            [6, 8, 14, 28],
            [7, 8, 15, 30],
            [4, 9, 13, 26],
            [5, 9, 14, 28],
            [6, 9, 15, 30],
            [7, 9, 16, 32]
        ];
    }

    public function test_size_for_default_params()
    {
        $guid = GUID::create();
        $this->assertEquals(16, \strlen($guid->asBinaryString()));
    }

    /**
     * @param int $nTimeBytes
     * @param int $nRandomBytes
     * @param int $expectedBinLength
     * @param int $expectedHexLength
     *
     * @dataProvider validParamsProvider
     */
    public function test_size_for_valid_custom_params(
        int $nTimeBytes,
        int $nRandomBytes,
        int $expectedBinLength,
        int $expectedHexLength
    ) {
        $guid = GUID::create($nTimeBytes, $nRandomBytes);
        $this->assertEquals($expectedBinLength, \strlen($guid->asBinaryString()));
        $this->assertEquals($expectedHexLength, \strlen($guid->asHexString()));
    }
}
