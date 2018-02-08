<?php

namespace Fiedsch\Quancept\Analysis;

error_reporting(E_ALL & ~E_NOTICE);

use Fiedsch\Quancept\Helper;
use Fiedsch\Quancept\Logs\Qca;

/**
 * Store and analyze AccountsResults from parsing Quancept log files
 *
 * @package Fiedsch\Quancept
 * @author Andreas Fieger
 */
class QcaResults
{
    /**
     * Names for input data columns
     */
    const STUDYNAME = 'studyname';
    const USERNAME = 'username';
    const USERNUMBER = 'usernumber';
    const INTERVIEWSTARTTIMESTAMP = 'interviewstarttimestamp';
    const INTERVIEWENDTIMESTAMP = 'interviewendtimestamp';
    const INTERVIEWENDTIME_HUMANREADABLE = 'interviewendtime_humanreadable';
    const DURATIONINTERVIEW = 'durationinterview';
    const DURATIONTELEPHONE = 'durationtelephone';
    const DURATIONEND = 'durationend';
    const TIPCODE = 'tipcode';
    const SIGNAL = 'signal';
    const ISRESTART = 'isrestart';
    const ISSTOPPED = 'isstopped';
    const PREVTIPCODE = 'prevtipcode';
    const SERIAL = 'serial';
    const LASTQUESTION = 'lastquestion';
    const SMSKEY = 'smskey';

    /*
     * computed data
     */
    const MOBFEST = 'mobfest';
    const DAY = 'day';
    const HOUR = 'hour';
    const DURATIONTOTAL = 'durationtotal';

    /**
     * Names for the types of data collection
     */
    const BY_DAY = 'day';
    const BY_INTERVIEWER = 'interviewer';

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
     * Return the interviewer names found in the analysis data
     * @return array
     */
    public function getInterviewer()
    {
        return array_keys($this->data[self::BY_INTERVIEWER]);
    }

    /**
     * Return the days found in the analysis data
     * @return array
     */
    public function getDays()
    {
        return array_keys($this->data[self::BY_DAY]);
    }

    /**
     * Add a record for the analysis "per interviewer"
     * @param string $interviewer
     * @param array $data
     */
    public function addInterviewerRecord($interviewer, $data) {
        if (!isset($this->data[self::BY_INTERVIEWER][$interviewer])) {
            $this->data[self::BY_INTERVIEWER][$interviewer] = self::getInitialData();
        }

        $mobfest = Helper::isMobileNumber($data[Qca::SMSKEY]) ? 'mobil' : 'fest'; // we use the telephone number as *key!

        $record = &$this->data[self::BY_INTERVIEWER][$interviewer];

        $day = date("ymd", $data[Qca::INTERVIEWSTARTTIMESTAMP]);

        $record[self::STUDYNAME][$day][$mobfest][$data[Qca::STUDYNAME]]++;
        $record[self::DURATIONTELEPHONE][$day][$mobfest] += $data[Qca::DURATIONTELEPHONE];
        $record[self::DURATIONINTERVIEW][$day][$mobfest] += $data[Qca::DURATIONINTERVIEW];
        $record[self::DURATIONEND][$day][$mobfest]       += $data[Qca::DURATIONEND];
        $record[self::TIPCODE][$day][$mobfest][$data[Qca::TIPCODE]]++;
        $record[self::SIGNAL][$day][$mobfest][$data[Qca::SIGNAL]]++;
        $record[self::ISRESTART][$day][$mobfest][$data[Qca::ISRESTART]]++;
        $record[self::ISSTOPPED][$day][$mobfest][$data[Qca::ISSTOPPED]]++;
        $record[self::PREVTIPCODE][$day][$mobfest][$data[Qca::PREVTIPCODE]]++;
        $record[self::LASTQUESTION][$day][$mobfest][$data[Qca::LASTQUESTION]]++;
        // computed columns
        $record[self::DAY][$day][$mobfest][date('ymd', $data[Qca::INTERVIEWSTARTTIMESTAMP])]++;
        $record[self::HOUR][$day][$mobfest][date('H', $data[Qca::INTERVIEWSTARTTIMESTAMP])]++;
        $record[self::DURATIONTOTAL][$day][$mobfest] +=
                $data[Qca::DURATIONTELEPHONE] +
                $data[Qca::DURATIONINTERVIEW] +
                $data[Qca::DURATIONEND];
    }

    /**
     * Add a record for the analysis "per day"
     * @param string $day
     * @param array $data
     */
    public function addDayRecord($day, $data) {
        if (!isset($this->data[self::BY_DAY][$day])) {
            $this->data[self::BY_DAY][$day] = self::getInitialData(true);
        }

        $mobfest = Helper::isMobileNumber($data[Qca::SMSKEY]) ? 'mobil' : 'fest'; // we use the telephone number as *key!

        $record = &$this->data[self::BY_DAY][$day];

        // Logic mainly the same as in addInterviewerRecord() except for
        // the fields like day that do not make sense here.

        $record[self::STUDYNAME][$day][$mobfest][$data[Qca::STUDYNAME]]++;
        $record[self::DURATIONTELEPHONE][$day][$mobfest] += $data[Qca::DURATIONTELEPHONE];
        $record[self::DURATIONINTERVIEW][$day][$mobfest] += $data[Qca::DURATIONINTERVIEW];
        $record[self::DURATIONEND][$day][$mobfest]       += $data[Qca::DURATIONEND];
        $record[self::TIPCODE][$day][$mobfest][$data[Qca::TIPCODE]]++;
        $record[self::SIGNAL][$day][$mobfest][$data[Qca::SIGNAL]]++;
        $record[self::ISRESTART][$day][$mobfest][$data[Qca::ISRESTART]]++;
        $record[self::ISSTOPPED][$day][$mobfest][$data[Qca::ISSTOPPED]]++;
        $record[self::PREVTIPCODE][$day][$mobfest][$data[Qca::PREVTIPCODE]]++;
        $record[self::LASTQUESTION][$day][$mobfest][$data[Qca::LASTQUESTION]]++;
        $record[self::USERNAME][$day][$mobfest][$data[Qca::USERNAME]]++;
        // computed columns
        $record[self::HOUR][$day][$mobfest][date('H', $data[Qca::INTERVIEWSTARTTIMESTAMP])]++;
        $record[self::DURATIONTOTAL][$day][$mobfest] +=
            $data[Qca::DURATIONTELEPHONE] +
            $data[Qca::DURATIONINTERVIEW] +
            $data[Qca::DURATIONEND];
    }


    /**
     * @param boolean $forDays create initial data for our analysis by days
     * @return array
     */
    protected static function getInitialData($forDays = false)
    {
        $result = [
            self::STUDYNAME               => [],
            //self::INTERVIEWSTARTTIMESTAMP => [], // too detailed / see computed columns
            //self::INTERVIEWENDTIMESTAMP   => [], // too detailed / see computed columns
            self::DURATIONTELEPHONE       => [],
            self::DURATIONINTERVIEW       => [],
            self::DURATIONEND             => [],
            self::TIPCODE                 => [],
            self::SIGNAL                  => [],
            self::ISRESTART               => [],
            self::ISSTOPPED               => [],
            self::PREVTIPCODE             => [],
            //self::SERIAL                  => [], // we can't analyze these
            self::LASTQUESTION            => [],
            //self::SMSKEY                  => [], // we can't analyze these
            // computed columns
            self::DAY                     => [],
            self::HOUR                    => [],
            self::DURATIONTOTAL           => [],
        ];
        if ($forDays) {
            unset($result[self::DAY]);
            $result[self::USERNAME] = [];
        }
        return $result;
    }

}