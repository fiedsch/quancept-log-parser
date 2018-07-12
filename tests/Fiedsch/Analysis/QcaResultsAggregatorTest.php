<?php

use Fiedsch\Quancept\Analysis\QcaResults;
use Fiedsch\Quancept\Analysis\QcaResultsAggregator;
use Fiedsch\Quancept\Analysis\ResultsAggregator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

class QcaResultsAggregatorTest extends TestCase
{
    /**
     * @var Fiedsch\Quancept\Analysis\ResultsAggregator;
     */
    protected $aggregator;

    /**
     * @var Fiedsch\Quancept\Analysis\LogfileResults;
     */
    protected $results;

    public function setUp()
    {
        $this->results = new QcaResults();
        // TODO: fill with data (what is the proper way to go?)
        $this->aggregator = new QcaResultsAggregator($this->results);
    }

    public function testAggregateBy()
    {
        /*
        // will not work until we provide data in setUp()
        $result = $this->aggregator->aggregate(ResultsAggregator::BY_DAY);
        foreach ([
                     AccountsResults::TRIES,
                     AccountsResults::STARTMINUTE,
                     AccountsResults::STOPMINUTE,
                     AccountsResults::LASTSTOPMINUTE,
                     AccountsResults::TOTALMINUTES,
                     AccountsResults::IDLEMINUTES,
                     AccountsResults::IDLEBREAKS,
                     AccountsResults::EXITCODES,
                     AccountsResults::TIPCODES,
                 ] as $key) {
            Assert::assertArrayHasKey($key, $result);
        }
        */
    }

    public function testAggregateByException()
    {
        $this->expectException(\RuntimeException::class);
        $this->aggregator->aggregate('not_defined');
    }
}
