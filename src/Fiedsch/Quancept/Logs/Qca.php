<?php

namespace Fiedsch\Quancept\Logs;

/**
 * QCA log parser constants
 *
 * @package Fiedsch\Quancept
 * @author Andreas Fieger
 */

use Fiedsch\Quancept\Analysis\QcaResults;

class Qca
{

    const INTERVIEW_ENDED_LASTQUESTION = 'SCHLUSS';
    const INTERVIEW_NOQUESTION = 'No_question';

    /*
     * "Column"-Indices
     */

    const STUDYNAME = 0;
    const USERNAME = 1;
    const USERNUMBER = 2;
    const INTERVIEWSTARTTIMESTAMP = 3;
    const INTERVIEWENDTIMESTAMP = 4;
    const INTERVIEWENDTIME_HUMANREADABLE = 5;
    const DURATIONINTERVIEW = 6;
    const DURATIONTELEPHONE = 7;
    const DURATIONEND = 8;
    const TIPCODE = 9;
    const SIGNAL = 10;
    const ISRESTART = 11;
    const ISSTOPPED = 12;
    const PREVTIPCODE = 13;
    const SERIAL = 14;
    const LASTQUESTION = 15;
    const SMSKEY = 16;

    /**
     * @var array predefined configuartion array for all standard columns
     * provided in the *.qca file.
     */
    const ALL_COLUMNS = [
        QcaResults::STUDYNAME => Qca::STUDYNAME,
        QcaResults::USERNAME => Qca::USERNAME,
        QcaResults::USERNUMBER => Qca::USERNUMBER,
        QcaResults::INTERVIEWSTARTTIMESTAMP => Qca::INTERVIEWSTARTTIMESTAMP,
        QcaResults::INTERVIEWENDTIMESTAMP => Qca::INTERVIEWENDTIMESTAMP,
        QcaResults::INTERVIEWENDTIME_HUMANREADABLE => Qca::INTERVIEWENDTIME_HUMANREADABLE,
        QcaResults::DURATIONINTERVIEW => Qca::DURATIONINTERVIEW,
        QcaResults::DURATIONTELEPHONE => Qca::DURATIONTELEPHONE,
        QcaResults::DURATIONEND => Qca::DURATIONEND,
        QcaResults::TIPCODE => Qca::TIPCODE,
        QcaResults::SIGNAL => Qca::SIGNAL,
        QcaResults::ISRESTART => Qca::ISRESTART,
        QcaResults::ISSTOPPED => Qca::ISSTOPPED,
        QcaResults::PREVTIPCODE => Qca::PREVTIPCODE,
        QcaResults::SERIAL => Qca::SERIAL,
        QcaResults::LASTQUESTION => Qca::LASTQUESTION,
        QcaResults::SMSKEY => Qca::SMSKEY,
    ];

}
