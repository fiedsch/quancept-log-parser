<?php

namespace Fiedsch\Quancept\Analysis;

error_reporting(E_ALL & ~E_NOTICE);

use Fiedsch\Quancept\Helper;

/**
 * Store and analyze AccountsResults from parsing Quancept log files
 *
 * @package Fiedsch\Quancept
 * @author Andreas Fieger
 */
class AccountsResults
{
    /**
     * Names for input data columns
     */
    const INTERVIEWER = 'interviewer';
    const SMSKEY      = 'smskey';
    const TIMESTRIED  = 'timestried';
    const START_DAY   = 'start_day';
    const START_TIME  = 'start_time';
    const DURATION    = 'duration';
    const TIPCODE     = 'tipcode';
    const EXITCODE    = 'exitcode';
    const QUEUENAME   = 'queuename';
    const QUEUENUMBER = 'queuenumber';

    /**
     * Names for results data columns
     */
    const TRIES = 'tries';
    const STARTMINUTE = 'startminute';
    const STOPMINUTE = 'stopminute';
    const LASTSTOPMINUTE = 'laststopminute';
    const TOTALMINUTES = 'totalminutes';
    const IDLEMINUTES = 'idleminutes';
    const IDLEBREAKS = 'idlebreaks';
    const EXITCODES = 'exitcodes';
    const TIPCODES = 'tipcodes';

    /**
     * Names for the types of data collection
     */
    const BY_DAY = 'day';
    const BY_INTERVIEWER = 'interviewer';


    /**
     * Split "Mobilfunk" (mobile telephony) and "Festnetz" (landline network) numbers
     */
    const MOBIL = 'mobil';
    const FEST = 'fest';

    /**
     * Maximale Differenz zwischen zwei Interviewzeitpunkten, die noch nicht
     * als Pause (d.h. Interviewer ausgeloggt) gewertet wird. Angabe in Minuten.
     * Angewendet wird diese Zeitangabe auf die Differenz zwischen Endzeitpunkt
     * eines Interviews und Startzeitpunkt des nächsten Interviews.
     * @var integer
     */
    const DELTAINOUT = 5;

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
        $result = $this->data;
        if ($result[self::BY_INTERVIEWER]) {
            foreach ($result[self::BY_INTERVIEWER] as $interviewer => &$data) {
                // remove internal data
                unset($data[self::LASTSTOPMINUTE]);
            }
        }
        return $result;
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
    public function addInterviewerRecord($interviewer, $data) {
        if (!isset($this->data[self::BY_INTERVIEWER][$interviewer])) {
            $this->data[self::BY_INTERVIEWER][$interviewer] = self::getInitialData();
        }

        $mobfest = Helper::isMobileNumber($data[self::SMSKEY]) ? self::MOBIL : self::FEST; // we use the telephone number as *key! (TODO: this is not generic)

        $record = &$this->data[self::BY_INTERVIEWER][$interviewer];

        $record[self::TRIES][$data[self::START_DAY]][$mobfest]++;
        $record[self::TIPCODES][$data[self::START_DAY]][$mobfest][$data[self::TIPCODE]]++;
        $record[self::EXITCODES][$data[self::START_DAY]][$mobfest][$data[self::EXITCODE]]++;
        $record[self::TOTALMINUTES][$data[self::START_DAY]][$mobfest] += Helper::toMinutes($data[self::DURATION]);
        // get the time of the earliest and the latest record for this interviewer
        $minute = Helper::getMinutes($data[self::START_TIME]);
        if (!isset($record[self::STARTMINUTE][$data[self::START_DAY]])
            || $record[self::STARTMINUTE][$data[self::START_DAY]] > $minute) {
            $record[self::STARTMINUTE][$data[self::START_DAY]] = $minute;
        }
        $minute += Helper::toMinutes($data[self::DURATION]);
        if (!isset($record[self::STOPMINUTE][$data[self::START_DAY]])
            || $record[self::STOPMINUTE][$data[self::START_DAY]] < $minute) {
            $record[self::STOPMINUTE][$data[self::START_DAY]] = $minute;
        }
        // Differenz zwischen zwei Interviewzeitpunkten erfassen und prüfen, ob dies
        // bereits als Pause (d.h. Interviewer ausgeloggt) gewertet wird soll. Die
        // Pause zwischen zwei Interviews wird  aus der Differenz zwischen Endzeitpunkt
        // eines Interviews und Startzeitpunkt des nächsten Interviews des gleichen
        // Interviewers gewertet!
        // Die Stopzeit des vorhergehende Datensatzes auswerten (sofern es eine gibt)
        $laststopminute = $record[self::LASTSTOPMINUTE][$data[self::START_DAY]] ?: 24*60;
        $startminute = Helper::getMinutes($data[self::START_TIME]);
        if ($laststopminute > 0 && ($laststopminute + self::DELTAINOUT < $startminute)) {
            $record[self::IDLEMINUTES][$data[self::START_DAY]] += $startminute - $laststopminute;
            $record[self::IDLEBREAKS][$data[self::START_DAY]]++;
        }
        $record[self::LASTSTOPMINUTE][$data[self::START_DAY]] = $startminute + Helper::toMinutes($data[self::DURATION]);
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

        $mobfest = Helper::isMobileNumber($data[self::SMSKEY]) ? self::MOBIL : self::FEST; // we use the telephone number as *key! (TODO: this is not generic)

        $record = &$this->data[self::BY_DAY][$day];

        // Logic mainly the same as in addInterviewerRecord() except for
        // the computation of breaks that only makes sense on a
        // per interviewer base.
        $record[self::TRIES][$mobfest]++;
        $record[self::TIPCODES][$mobfest][$data[self::TIPCODE]]++;
        $record[self::EXITCODES][$mobfest][$data[self::EXITCODE]]++;
        $record[self::TOTALMINUTES][$mobfest] += Helper::toMinutes($data[self::DURATION]);
        $minute = Helper::getMinutes($data[self::START_TIME]);
        if (!isset($record[self::STARTMINUTE])
            || $record[self::STARTMINUTE] > $minute) {
            $record[self::STARTMINUTE] = $minute;
        }
        $minute += Helper::toMinutes($data[self::DURATION]);
        if (!isset($record[self::STOPMINUTE])
            || $record[self::STOPMINUTE] < $minute) {
            $record[self::STOPMINUTE] = $minute;
        }
    }

    /**
     * @param boolean $forDays create initial data for our analysis by days
     * @return array
     */
    protected static function getInitialData($forDays = false)
    {
        $result = [
            self::TRIES            => [],  // Gesamtzahl Versuche (vorgelegte Nummern)
            self::STARTMINUTE      => [],  // Anfangszeit erstes Interview
            self::STOPMINUTE       => [],  // Endzeit letztes Interview des Tages
            self::LASTSTOPMINUTE   => [],  // Endzeit letztes aus accounts.sms gelesenes Interview
            self::TOTALMINUTES     => [],  // Gesamtzeit (Loginzeit)
            self::IDLEMINUTES      => [],  // Gesamtzeit die der Interviewer auf Status Pause war
            self::IDLEBREAKS       => [],  // Anzahl der Perioden die in "idleminutes" summiert werden
            self::EXITCODES        => [],  // alle Quancept-Exitcodes
            self::TIPCODES         => [],  // alle Tipcodes
        ];
        if ($forDays) {
            // here these values are scalars
            $result[self::TRIES] = null;
            $result[self::STARTMINUTE] = null;
            $result[self::STOPMINUTE] = null;
            $result[self::TOTALMINUTES] = null;
            unset($result[self::LASTSTOPMINUTE]);
            unset($result[self::IDLEMINUTES]);
            unset($result[self::IDLEBREAKS]);
        }
        return $result;
    }

}