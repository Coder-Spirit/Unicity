<?php
declare(strict_types=1);

namespace Unicity\Tests\GUID;

use PHPUnit\Framework\TestCase;
use Unicity\GUID;

/**
 * @covers \Unicity\GUID::equals
 */
class equalsTest extends TestCase
{
    public function testEquality()
    {
        $guid1 = GUID::create();
        $guid2 = GUID::create();
        $guid3 = GUID::create();
        $guid4 = GUID::create();

        $this->assertTrue($guid1->equals($guid1));
        $this->assertTrue($guid2->equals($guid2));
        $this->assertTrue($guid3->equals($guid3));
        $this->assertTrue($guid4->equals($guid4));
    }

    public function testInequality()
    {
        $guid1 = GUID::create();
        $guid2 = GUID::create();
        $guid3 = GUID::create();
        $guid4 = GUID::create();

        $this->assertFalse($guid1->equals($guid2));
        $this->assertFalse($guid1->equals($guid3));
        $this->assertFalse($guid1->equals($guid4));

        $this->assertFalse($guid2->equals($guid1));
        $this->assertFalse($guid2->equals($guid3));
        $this->assertFalse($guid2->equals($guid4));

        $this->assertFalse($guid3->equals($guid1));
        $this->assertFalse($guid3->equals($guid2));
        $this->assertFalse($guid3->equals($guid4));

        $this->assertFalse($guid4->equals($guid1));
        $this->assertFalse($guid4->equals($guid2));
        $this->assertFalse($guid4->equals($guid3));
    }
}
