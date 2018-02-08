<?php

namespace Fiedsch\Quancept\Logs;

/**
 * QCA log parser constants
 *
 * @package Fiedsch\Quancept
 * @author Andreas Fieger
 */

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



}