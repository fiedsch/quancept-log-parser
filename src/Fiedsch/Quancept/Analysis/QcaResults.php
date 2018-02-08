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


}