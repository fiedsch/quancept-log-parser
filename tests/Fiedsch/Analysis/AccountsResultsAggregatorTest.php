<?php

use Fiedsch\Quancept\Analysis\AccountsResults;
use Fiedsch\Quancept\Analysis\AccountsResultsAggregator;
use Fiedsch\Quancept\Analysis\ResultsAggregator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

class AccountsResultsAggregatorTest extends TestCase
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
        $this->results = new AccountsResults();
        // TODO: fill with data (what is the proper way to go?)
        $this->aggregator = new AccountsResultsAggregator($this->results);
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
                     AccountsResults::TOTALDURATION,
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
