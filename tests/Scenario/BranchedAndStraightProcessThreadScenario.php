<?php

namespace App\Test\Scenario;

use App\Test\Traits\RetrievalTrait;
use App\Test\Utilities\ProcessThread;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;

class BranchedAndStraightProcessThreadScenario implements FixtureScenarioInterface
{

    use RetrievalTrait;

    /**
     * Load method requires a job object, and will load one if
     * one is not provided
     * @inheritDoc
     */
    public function load(...$args)
    {
        $job = $args[0];

        ProcessThread::generate($job);
        ProcessThread::generate($job, 11, 20, 5);
        ProcessThread::generate($job, 21, 30, 15);
        ProcessThread::generate($job, 31, 40);
    }

}
