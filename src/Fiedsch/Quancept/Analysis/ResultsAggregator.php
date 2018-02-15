<?php

namespace Fiedsch\Quancept\Analysis;

error_reporting(E_ALL & ~E_NOTICE);

/**
 * Aggregate Results. E.g. total over all days stored in the results
 *
 * @package Fiedsch\Quancept
 * @author Andreas Fieger
 */
abstract class ResultsAggregator
{
    const BY_DAY = 'day';
    const BY_INTERVIEWER = 'interviewer';
    const TOTAL = 'total';

    /**
     * @var object
     */
    protected $results;

    /**
     * ResultsAggregator constructor.
     *
     * @param object $results
     */
    public function __construct($results)
    {
        $this->results = $results;
    }

    /**
     * @param string $target self::BY_DAY or self::BY_INTERVIEWER or self::TOTAL
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function aggregate($target = self::BY_INTERVIEWER)
    {
        switch ($target) {
            case self::BY_DAY:
                return $this->aggregateByDay();
                break;
            case self::BY_INTERVIEWER:
                return $this->aggregateByInterviewer();
                break;
            case self::TOTAL:
                return $this->aggregateTotal();
                break;
            default:
                throw new \RuntimeException("invalid target '$target'");
        }
    }

    /**
     * Aggreagte by day (nothing to do as days-data are already aggregated by interviewer
     * (i.e. not split by interviewer)
     *
     * @see AccountsResults::addDayRecord() and QcaResults::addDayRecord()
     */
    protected function aggregateByDay()
    {
        // no-op as day-data are already aggregated
        return $this->results->getData()[QcaResults::BY_DAY];
    }

    /**
     * Aggreagte by interviewer (i.e. aggregate days and keep interviewers)
     *
     * @throws \Exception
     */
    protected abstract function aggregateByInterviewer();

    /**
     * Aggregate grand total (interviewer and days)
     */
    protected abstract function aggregateTotal();

    /**
     * @param string $interviewer
     * @param string $statistic
     * @param boolean $splitMobFest
     * @return array|int
     */
    protected function internalAggregateInterviewerScalar($interviewer, $statistic, $splitMobFest)
    {
        $result = $splitMobFest ? [AccountsResults::MOBIL => 0, AccountsResults::FEST => 0] : 0;
        $interviewerData = $this->results->getInterviewerData($interviewer);
        if (!isset($interviewerData[$statistic])) {
            return $result;
        }
        foreach ($interviewerData[$statistic] as $day => $data) {
            if ($splitMobFest) {
                $result[AccountsResults::MOBIL] += isset($data[AccountsResults::MOBIL]) ? $data[AccountsResults::MOBIL] : 0;
                $result[AccountsResults::FEST] += isset($data[AccountsResults::FEST]) ? $data[AccountsResults::FEST] : 0;
            } else {
                $result += $data;
            }
        }
        return $result;

    }

    /**
     * @param string $interviewer
     * @param string $statistic
     * @param boolean $splitMobFest
     * @return array|int
     */
    protected function internalAggregateInterviewerArray($interviewer, $statistic, $splitMobFest)
    {
        $result = $splitMobFest ? [AccountsResults::MOBIL => [], AccountsResults::FEST => []] : 0;
        $interviewerData = $this->results->getInterviewerData($interviewer);
        if (!isset($interviewerData[$statistic])) {
            return $result;
        }
        foreach ($interviewerData[$statistic] as $day => $data) {
            if ($splitMobFest) {
                if (isset($data[AccountsResults::MOBIL])) {
                    foreach ($data[AccountsResults::MOBIL] as $k => $v) {
                        $result[AccountsResults::MOBIL][$k] += $v;
                    }
                }
                if (isset($data[AccountsResults::FEST])) {
                    foreach ($data[AccountsResults::FEST] as $k => $v) {
                        $result[AccountsResults::FEST][$k] += $v;
                    }
                }
            } else {
                foreach ($data as $k => $v) {
                    $result[$k] += $v;
                }
            }
        }
        return $result;
    }

    /**
     * @param array $aggregatedByInterviewer
     * @param string $statistic
     * @param boolean $splitMobFest
     * @return array|int
     */
    protected function internalAggregateDaysScalar(&$aggregatedByInterviewer, $statistic, $splitMobFest)
    {
        $result = $splitMobFest ? [AccountsResults::MOBIL => 0, AccountsResults::FEST => 0] : 0;
        foreach ($this->results->getInterviewer() as $interviewer) {
            if (!isset($aggregatedByInterviewer[$interviewer][$statistic])) {
                continue;
            }
            if ($splitMobFest) {
                $result[AccountsResults::MOBIL] += isset($aggregatedByInterviewer[$interviewer][$statistic][AccountsResults::MOBIL]) ? $aggregatedByInterviewer[$interviewer][$statistic][AccountsResults::MOBIL] : 0;
                $result[AccountsResults::FEST] += isset($aggregatedByInterviewer[$interviewer][$statistic][AccountsResults::FEST]) ? $aggregatedByInterviewer[$interviewer][$statistic][AccountsResults::FEST] : 0;
            } else {
                $result += $aggregatedByInterviewer[$interviewer][$statistic];
            }
        }
        return $result;
    }

    /**
     * @param array $aggregatedByInterviewer
     * @param string $statistic
     * @param boolean $splitMobFest
     * @return array|int
     */
    protected
    function internalAggregateDaysArray(&$aggregatedByInterviewer, $statistic, $splitMobFest)
    {
        $result = $splitMobFest ? [AccountsResults::MOBIL => [], AccountsResults::FEST => []] : 0;
        foreach ($this->results->getInterviewer() as $interviewer) {
            if (!isset($aggregatedByInterviewer[$interviewer][$statistic])) {
                continue;
            }
            if ($splitMobFest) {
                if (isset($aggregatedByInterviewer[$interviewer][$statistic][AccountsResults::MOBIL])) {
                    foreach ($aggregatedByInterviewer[$interviewer][$statistic][AccountsResults::MOBIL] as $k => $v) {
                        $result[AccountsResults::MOBIL][$k] += $v;
                    }
                }
                if (isset($aggregatedByInterviewer[$interviewer][$statistic][AccountsResults::FEST])) {
                    foreach ($aggregatedByInterviewer[$interviewer][$statistic][AccountsResults::FEST] as $k => $v) {
                        $result[AccountsResults::FEST][$k] += $v;
                    }
                }
            } else {
                foreach ($aggregatedByInterviewer[$interviewer][$statistic] as $k => $v) {
                    $result[$k] += $v;
                }
            }
        }
        return $result;
    }
}