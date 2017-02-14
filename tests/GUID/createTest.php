<?php
declare(strict_types=1);

namespace Unicity\Tests\GUID;

use PHPUnit\Framework\TestCase;
use Unicity\GUID;

/**
 * @covers \Unicity\GUID::__construct
 * @covers \Unicity\GUID::create
 * @covers \Unicity\GUID::getTimeBytes
 * @covers \Unicity\GUID::getAdjustedTimeBytes
 */
class createTest extends TestCase
{
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

    /**
     * @param int $nTimeBytes
     * @param int $pause
     *
     * @dataProvider timeSettingsProvider
     */
    public function test_time_order(int $nTimeBytes, int $pause)
    {
        $guid1 = GUID::create($nTimeBytes);
        usleep($pause);
        $guid2 = GUID::create($nTimeBytes);

        $this->assertTrue($guid2->asBinaryString() > $guid1->asBinaryString());
        $this->assertTrue($guid2->asHexString() > $guid1->asHexString());
    }

    public function validParamsProvider()
    {
        return [
            // nRandomBytes "underflow"
            [4, 1, 6, 12],
            [5, 1, 7, 14],
            [6, 1, 8, 16],
            [7, 1, 9, 18],

            // nTimeBytes "underflow"
            [3, 7, 11, 22],
            [3, 8, 12, 24],
            [3, 9, 13, 26],

            [4, 2, 6, 12],
            [5, 2, 7, 14],
            [6, 2, 8, 16],
            [7, 2, 9, 18],
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
            [7, 9, 16, 32],

            // nRandomBytes "saturation"
            [4, 10, 13, 26],
            [5, 10, 14, 28],
            [6, 10, 15, 30],
            [7, 10, 16, 32],

            // nTimeBytes "saturation"
            [8, 7, 14, 28],
            [8, 8, 15, 30],
            [8, 9, 16, 32],
            [8, 10, 16, 32],
        ];
    }

    public function timeSettingsProvider()
    {
        return [
            [7, 1],
            [6, 1000],
            [5, 1000000],
            [4, 1000000]
        ];
    }
}
