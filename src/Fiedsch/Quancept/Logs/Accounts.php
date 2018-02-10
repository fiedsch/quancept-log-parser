<?php

namespace Fiedsch\Quancept\Logs;

use \Fiedsch\Quancept\Analysis\AccountsResults;

/**
 * Accounts log parser constants
 *
 * @package Fiedsch\Quancept
 * @author Andreas Fieger
 */

class Accounts
{
    /**
     * @var string the "interviewer" name used for the QTS (Dialer)
     */
    const AGENT_QTS = 'qts';

    /**
     *
     * Exit-Codes as defined by Quancept. These are systemsettings,
     * i.e. identical for all procects (but not necessarily for all
     * systems :-o).
     *
     * @var array
     */
    const EXITCODES = [
        1  => 'complete',
        2  => 'quit',
        3  => 'error',
        5  => 'quota',
        6  => 'early completion',
        7  => 'stop statement in script',
        8  => 'abandon',
        9  => 'stop (no data written)',
        10 => 'stop (data written)',
        11 => 'reschedule',
    ];

    /*
     * "Column"-Indices
     */

    const RECORD_KEY_FROM = 0;
    const RECORD_KEY_TO = 14;

    const TIMESTRIED_FROM = 15;
    const TIMESTRIED_TO = 18;

    const START_DATE_FROM = 19;
    const START_DATE_TO = 25;

    const START_TIME_FROM = 26;
    const START_TIME_TO = 31;

    const DURATION_FROM = 32;
    const DURATION_TO = 37;

    const TIPCODE_FROM = 37;
    const TIPCODE_TO = 40;

    const EXITCODE_FROM = 41;
    const EXITCODE_TO = 43;

    const QUEUENUMBER_FROM = 44;
    const QUEUENUMBER_TO = 46;

    const QUEUENAME_FROM = 47;
    const QUEUENAME_TO = 59;

    const INTERVIEWER_FROM = 60;
    const INTERVIEWER_TO = 72;

    /**
     * @var array predefined configuartion array to read all columns
     * to be used with (e.g.) Fiedsch\Data\File\FixedWidthReader.
     */
    const ALL_COLUMNS = [
        AccountsResults::KEY         => ['from' => Accounts::RECORD_KEY_FROM, 'to' => Accounts::RECORD_KEY_TO],
        AccountsResults::TIMESTRIED  => ['from' => Accounts::TIMESTRIED_FROM, 'to' => Accounts::TIMESTRIED_TO],
        AccountsResults::START_DAY   => ['from' => Accounts::START_DATE_FROM, 'to' => Accounts::START_DATE_TO],
        AccountsResults::START_TIME  => ['from' => Accounts::START_TIME_FROM, 'to' => Accounts::START_TIME_TO],
        AccountsResults::DURATION    => ['from' => Accounts::DURATION_FROM, 'to' => Accounts::DURATION_TO],
        AccountsResults::TIPCODE     => ['from' => Accounts::TIPCODE_FROM, 'to' => Accounts::TIPCODE_TO],
        AccountsResults::EXITCODE    => ['from' => Accounts::EXITCODE_FROM, 'to' => Accounts::EXITCODE_TO],
        AccountsResults::QUEUENAME   => ['from' => Accounts::QUEUENAME_FROM, 'to' => Accounts::QUEUENAME_TO],
        AccountsResults::QUEUENUMBER => ['from' => Accounts::QUEUENUMBER_FROM, 'to' => Accounts::QUEUENUMBER_TO],
        AccountsResults::INTERVIEWER => ['from' => Accounts::INTERVIEWER_FROM, 'to' => Accounts::INTERVIEWER_TO],
    ];

}