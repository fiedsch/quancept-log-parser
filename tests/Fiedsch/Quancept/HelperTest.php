<?php

use Fiedsch\Quancept\Helper;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

class HelperTest extends TestCase
{

    public function testIsBetween()
    {
        Assert::assertTrue(Helper::isBetween('180916', '11:55', '180201'));
        Assert::assertTrue(Helper::isBetween('180916', '11:55', '180201'));
        Assert::assertTrue(Helper::isBetween('180916', '11:55', '180201 02:34'));
        Assert::assertTrue(Helper::isBetween('180916', '11:55', '180201 12:15', '181030'));
        Assert::assertTrue(Helper::isBetween('180916', '11:55', '180201 12:15', '181030 02:35'));
    }

    public function testTsIsBetween()
    {
        $timestamp = time();
        Assert::assertTrue(Helper::tsIsBetween($timestamp));
        Assert::assertTrue(Helper::tsIsBetween($timestamp, '180201', '181030'));
    }

    public function testMakeTimestamp()
    {
        $ts = 1158429600; // 2006-09-16 20:00 in Europe/Berlin local time
        Assert::assertEquals($ts, Helper::makeTimestamp("060916 20:00"));
        Assert::assertEquals($ts, Helper::makeTimestamp("060916 20:00", 'Europe/Berlin'));
        Assert::assertEquals($ts + 3600, Helper::makeTimestamp("060916 20:00", 'Europe/London'));
        $this->expectException(\RuntimeException::class);
        // time not provided should throw an exception
        Helper::makeTimestamp("060916");
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

    public function testToMinutes()
    {
        Assert::assertEquals(1.5, Helper::toMinutes(90)); // defaults to 'fractional'
        Assert::assertEquals(1.5, Helper::toMinutes(90, 'fractional'));
        Assert::assertEquals(1, Helper::toMinutes(90, 'int'));
        Assert::assertEquals(1, Helper::toMinutes(90, 'floor'));
        Assert::assertEquals(2, Helper::toMinutes(90, 'ceil'));
    }

    public function testGetMinutes()
    {
        Assert::assertEquals(60, Helper::getMinutes('01:00'));
        Assert::assertEquals(60*24 - 1, Helper::getMinutes('23:59'));
    }

}