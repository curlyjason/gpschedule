<?php

namespace App\Test\Scenario;

use App\Model\Entity\Job;
use App\Test\Factory\JobFactory;
use App\Test\Traits\RetrievalTrait;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use function PHPUnit\Framework\isEmpty;

class SingleStreamProcessScenario implements FixtureScenarioInterface
{

    use RetrievalTrait;

    /**
     * Load method requires a job object, and will load one if
     * one is not provided
     * @inheritDoc
     */
    public function load(...$args)
    {
        $args = (func_get_args());
        debug($args);
        if(isEmpty($args) || ! $args[0] instanceof Job){
            $job = JobFactory::make()->persist();
        } else {
            $job = $args[0];
        }

        debug($job);
    }
}
