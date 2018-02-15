<?php

namespace Fiedsch\Quancept\Analysis;

error_reporting(E_ALL & ~E_NOTICE);

// use Fiedsch\Quancept\Helper;

/**
 * Aggregate QcaResults. E.g. total over all days stored in the results
 *
 * @package Fiedsch\Quancept
 * @author Andreas Fieger
 */
class QcaResultsAggregator extends ResultsAggregator
{
    /**
     * QcaResultsAggregator constructor.
     *
     * @param QcaResults $results the results we want to aggregate
     */
    public function __construct(QcaResults $results)
    {
        parent::__construct($results);
    }

    /**
     * Aggreagte by interviewer (i.e. aggregate days and keep interviewers)
     *
     * @throws \Exception
     */
    protected function aggregateByInterviewer()
    {
        $result = [];
        foreach ($this->results->getInterviewer() as $interviewer) {
            $result[$interviewer] = [];
            foreach ([
                         'studyname'         => 'mobfest:array',
                         'durationtelephone' => 'mobfest:scalar',
                         'durationinterview' => 'mobfest:scalar',
                         'durationend'       => 'mobfest:scalar',
                         'tipcode'           => 'mobfest:array',
                         'signal'            => 'mobfest:array',
                         'isrestart'         => 'mobfest:array',
                         'isstopped'         => 'mobfest:array',
                         'prevtipcode'       => 'mobfest:array',
                         'lastquestion'      => 'mobfest:array',
                         'hour'              => 'mobfest:array',
                         'durationtotal'     => 'mobfest:scalar',
                         'username'          => 'mobfest:array',
                     ] as $statistic => $datastructure) {
                $datastructurecomponents = explode(":", $datastructure);
                if ($datastructurecomponents[0] === 'mobfest') {
                    $splitMobFest = true;
                    array_shift($datastructurecomponents);
                } else {
                    $splitMobFest = false;
                }
                $type = $datastructurecomponents[0];
                switch ($type) {
                    case 'scalar':
                        $result[$interviewer][$statistic] = $this->internalAggregateInterviewerScalar($interviewer, $statistic, $splitMobFest);
                        break;
                    case 'array':
                        $result[$interviewer][$statistic] = $this->internalAggregateInterviewerArray($interviewer, $statistic, $splitMobFest);
                        break;
                    default:
                        throw new \Exception("undefiend type '$type");
                }
            }
        }
        return $result;
    }


    /**
     * Aggregate grand total (interviewer and days)
     */
    protected function aggregateTotal()
    {
        $aggregatedByInterviewer = $this->aggregate(self::BY_INTERVIEWER);
        $result = [];
        foreach ([
                     'studyname'         => 'mobfest:array',
                     'durationtelephone' => 'mobfest:scalar',
                     'durationinterview' => 'mobfest:scalar',
                     'durationend'       => 'mobfest:scalar',
                     'tipcode'           => 'mobfest:array',
                     'signal'            => 'mobfest:array',
                     'isrestart'         => 'mobfest:array',
                     'isstopped'         => 'mobfest:array',
                     'prevtipcode'       => 'mobfest:array',
                     'lastquestion'      => 'mobfest:array',
                     'hour'              => 'mobfest:array',
                     'durationtotal'     => 'mobfest:scalar',
                     'username'          => 'mobfest:array',
                 ] as $statistic => $datastructure) {

            $datastructurecomponents = explode(":", $datastructure);
            if ($datastructurecomponents[0] === 'mobfest') {
                $splitMobFest = true;
                array_shift($datastructurecomponents);
            } else {
                $splitMobFest = false;
            }
            $type = $datastructurecomponents[0];
            switch ($type) {
                case 'scalar':
                    $result[$statistic] = $this->internalAggregateDaysScalar($aggregatedByInterviewer, $statistic, $splitMobFest);
                    break;
                case 'array':
                    $result[$statistic] = $this->internalAggregateDaysArray($aggregatedByInterviewer, $statistic, $splitMobFest);
                    break;
            }
        }
        return $result;
    }

}