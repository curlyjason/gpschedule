<?php

namespace App\Test\TestCase\Utilities;

use App\Test\TestDoubles\ProcessSetDouble;
use App\Test\Traits\RetrievalTrait;
use App\Utilities\ProcessSet;
use Cake\TestSuite\TestCase;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;

class ProcessSetTest extends TestCase
{
    use ScenarioAwareTrait;
    use RetrievalTrait;

    public function test_construct()
    {
        $this->loadFixtureScenario('SingleStreamProcess');
        $SetManager = new ProcessSetDouble($this->getRecords('Processes'));
    }

}
