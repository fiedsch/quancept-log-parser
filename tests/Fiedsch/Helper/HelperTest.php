<?php

use Fiedsch\Quancept\Helper;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

class HelperTest extends TestCase
{

    public function testBetween()
    {
        Assert::assertTrue(Helper::isBetween('180916', '11:55', '180201'));
        Assert::assertTrue(Helper::isBetween('180916', '11:55', '180201'));
        Assert::assertTrue(Helper::isBetween('180916', '11:55', '180201 02:34'));
        Assert::assertTrue(Helper::isBetween('180916', '11:55', '180201 12:15', '181030'));
        Assert::assertTrue(Helper::isBetween('180916', '11:55', '180201 12:15', '181030 02:35'));
    }

    public function testIsMobileNumber()
    {
        Assert::assertTrue(Helper::isMobileNumber('01778966697'));
        Assert::assertFalse(Helper::isMobileNumber('0898966697'));
        Assert::assertFalse(Helper::isMobileNumber(''));
    }

    public function testPatchDate()
    {
        Assert::assertEquals('180208 00:00', Helper::patchDate('180208'));
        Assert::assertEquals('180208 16:42', Helper::patchDate('180208', '16:42'));
        $this->expectException(\RuntimeException::class);
        Helper::patchDate("20060916");
    }
}