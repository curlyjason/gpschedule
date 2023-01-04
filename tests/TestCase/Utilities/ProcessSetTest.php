<?php

namespace App\Test\TestCase\Utilities;

use App\Test\Factory\JobFactory;
use Cake\TestSuite\TestCase;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;

class ProcessSetTest extends TestCase
{
    use ScenarioAwareTrait;

    public function test_construct()
    {
        $job = JobFactory::make()->persist();
        $this->loadFixtureScenario('SingleStreamProcess', $job);
    }
}
