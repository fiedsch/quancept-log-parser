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
     *
     * @param string $date date of the log entry
     * @param string $time timestamp of the log entry
     * @param string|null $start date and time to filter entry (e.g. '180110 00:00' or '180110' where '00:00' will be assumed)
     * @param string|null $stop date and time to filter entry ('180120 23:59' as $start (with '23:59' fallback))
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
     * @param string $date
     * @param string $time
     * @return string
     */
    protected static function patchDate($date, $time = '00:00')
    {
        if (preg_match("/^(\d{6})\s+(\d{2}:\d{2})$/", $date, $matches)) { return $matches[1].'_'.$matches[2]; }
        return $date . '_' . $time;
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
}