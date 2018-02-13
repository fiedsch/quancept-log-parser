<?php

namespace Fiedsch\Quancept;

/**
 * Helper functions
 *
 * @package Fiedsch\Quancept
 * @author Andreas Fieger
 */
class Helper
{

    /**
     * Does the current record lie between the two dates (and respective times)
     * Date/Time recorded in human readable format like "180328 13:45" for
     * 2018-03-28 13:45 (local time)
     *
     * @param string $date date of the log entry
     * @param string $time timestamp of the log entry
     * @param string|null $start date and time to filter entry (e.g. '180110 00:00' or '180110' where '00:00' will be assumed)
     * @param string|null $stop date and time to filter entry ('180120 23:59' as $start (with '23:59' fallback))
     * @throws \RuntimeException
     * @return bool
     */
    public static function isBetween($date, $time, $start = null, $stop = null)
    {
        if (null === $start && null === $stop) {
            return true;
        }
        if (null !== $start) {
            $start = self::patchDate($start, '00:00');
        } else {
            $start = self::patchDate('000101', '00:00'); // 2000-01-01 00:00
        }
        if (null !== $stop)  {
            $stop  = self::patchDate($stop, '23:59');
        } else {
            $stop = self::patchDate('991231', '23:59'); // 2099-12-31 23:59
        }
        $datetime = self::patchDate($date, $time);
        if (strcmp($start, $datetime) <= 0 && strcmp($datetime, $stop) <= 0) {
            return true;
        }
        return false;
    }

    /**
     * Does the current record lie between the two dates (and respective times)
     * Date/Time recorded as Unix Timestamp
     *
     * @param integer $timestamp timestamp of the log entry
     * @param string|null $start date and time to filter entry (e.g. '180110 00:00' or '180110' where '00:00' will be assumed)
     * @param string|null $stop date and time to filter entry ('180120 23:59' as $start (with '23:59' fallback))
     * @throws \RuntimeException
     * @return bool
     */
    public static function tsIsBetween($timestamp, $start = null, $stop = null)
    {
        if (null === $start && null === $stop) {
            return true;
        }
        if (null !== $start) {
            $start = self::patchDate($start, '00:00');
        } else {
            $start = self::patchDate('000101', '00:00'); // 2000-01-01 00:00
        }
        if (null !== $stop)  {
            $stop  = self::patchDate($stop, '23:59');
        } else {
            $stop = self::patchDate('991231', '23:59'); // 2099-12-31 23:59
        }
        $start_ts = self::makeTimestamp($start);
        $stop_ts = self::makeTimestamp($stop);
        if ($start_ts <= $timestamp && $stop_ts >= $timestamp) {
            return true;
        }
        return false;
    }

    /**
     * @param string $datestimetring A date and time string formatted as "ymd H:i" (e.g. "180327 16:26" for "2018-03-27 16:26")
     * @param string $timezone
     * @return int
     * @throws \RuntimeException
     */
    public static function makeTimestamp($datestimetring, $timezone = 'Europe/Berlin')
    {
        if (!preg_match("/^\d{6} \d{2}:\d{2}$/", $datestimetring)) {
            throw new \RuntimeException("invalid date/time format: '$datestimetring'");
        }
        $dt = \DateTime::createFromFormat("ymd H:i" , $datestimetring, new \DateTimeZone($timezone));
        return $dt->getTimestamp();
    }

    /**
     * @param string $date
     * @param string $time
     * @throws \RuntimeException
     * @return string
     */
    public static function patchDate($date, $time = '00:00')
    {
        if (preg_match("/^(\d{6})\s+(\d{2}:\d{2})$/", $date, $matches)) {
            return $matches[1] . ' ' . $matches[2];
        }
        if (!preg_match("/^\d{6}$/", $date) || !preg_match("/^\d{2}:\d{2}$/", $time)) {
            throw new \RuntimeException("invalid date or time string supplied '$date' '$time'");
        }
        return $date .  ' ' . $time;
    }

    /**
     * @param string $tel a telephone number (including "area code" prefix)
     * @return bool true if this thelephone number is a mobile phone number (DE only!)
     */
    public static function isMobileNumber($tel)
    {
     //return preg_match("/^01/", $tel);
        return mb_substr($tel, 0, 2) === '01';
    }

    /**
     * @param integer $seconds the seconds to be converted to minutes
     * @param string $strategy one of 'fractional', 'int', 'ceil', 'floor'
     * @return double
     */
    public static function toMinutes($seconds, $strategy = 'fractional')
    {
        switch ($strategy) {
            case 'int':
                return intval($seconds/60);
                break;
            case 'ceil':
                return ceil($seconds/60);
                break;
            case 'floor':
                return floor($seconds/60);
                break;
            case 'fractional':
            default:
                return $seconds/60;
                break;
        }
    }
}
