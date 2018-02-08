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
}