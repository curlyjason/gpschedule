<?php

namespace App\Test\Scenario;

use App\Test\Traits\RetrievalTrait;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;

class SingleStreamProcessScenario implements FixtureScenarioInterface
{

    use RetrievalTrait;

    /**
     * @inheritDoc
     */
    public function load(...$args)
    {
        debug(func_get_arg(0));
    }
}
