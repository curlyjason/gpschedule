<?php

namespace App\Test\Scenario;

use App\Test\Traits\RetrievalTrait;
use App\Test\Utilities\ProcessThread;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;

class BranchedProcessThreadScenario implements FixtureScenarioInterface
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
        ProcessThread::generate($job, 111, 120, 105);
        ProcessThread::generate($job, 121, 130, 115);
    }

}
