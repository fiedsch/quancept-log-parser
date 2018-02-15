<?php

namespace Fiedsch\Quancept\Analysis;

error_reporting(E_ALL & ~E_NOTICE);

use Fiedsch\Quancept\Helper;

/**
 * Base class to store and analyze Results from parsing Quancept log files
 *
 * @package Fiedsch\Quancept
 * @author Andreas Fieger
 */
abstract class LogfileResults
{
    /*
     * computed data
    */
    const DAY = 'day';
    const HOUR = 'hour';

    /**
     * Names for the types of data collection
     */
    const BY_DAY = 'day';
    const BY_INTERVIEWER = 'interviewer';


    /**
     * Split "Mobilfunk" (mobile telephony) and "Festnetz" (landline network) numbers
     */
    const MOBFEST = 'mobfest';
    const MOBIL = 'mobil';
    const FEST = 'fest';

    /**
     * @var array
     */
    protected $data;

    /**
     * AccountsResults constructor.
     */
    public function __construct()
    {
        $this->data = [];
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param string $interviewer
     * @return array
     */
    public function getInterviewerData($interviewer)
    {
        if (isset($this->data[self::BY_INTERVIEWER][$interviewer])) {
            return $this->data[self::BY_INTERVIEWER][$interviewer];
        }
        return [];
    }

    /**
     * @param string $day
     * @return array
     */
    public function getDayData($day)
    {
        if (isset($this->data[self::BY_DAY][$day])) {
            return $this->data[self::BY_DAY][$day];
        }
        return [];
    }

    /**
     * Return the interviewer names found in the analysis data
     * @return array
     */
    public function getInterviewer()
    {
        if (isset($this->data[self::BY_INTERVIEWER])) {
            return array_keys($this->data[self::BY_INTERVIEWER]);
        }
        return [];
    }

    /**
     * Return the days found in the analysis data
     * @return array
     */
    public function getDays()
    {
        if (isset($this->data[self::BY_DAY])) {
            return array_keys($this->data[self::BY_DAY]);
        }
        return [];
    }

    /**
     * Add a record for the analysis "per interviewer"
     * @param string $interviewer
     * @param array $data
     */
    public abstract function addInterviewerRecord($interviewer, $data);

    /**
     * Add a record for the analysis "per day"
     * @param string $day
     * @param array $data
     */
    public abstract function addDayRecord($day, $data);

}