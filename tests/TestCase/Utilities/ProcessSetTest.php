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

        /**
         *
         */
        $lookup = [
            '' => ['a', 'b'],
            'a' => [],
            'b' => [],
        ];
        $result = [
            ['a'],
            ['b'],
        ];

        /**
         *
         */
        $lookup = [
            '' => ['a'],
            'a' => [],
        ];
        $result = [
            'a',
        ];

        /**
         *
         */
        $lookup = [
            '' => ['a', 'b'],
            'a' => ['a.a'],
            'a.a' => ['a.b'],
            'a.b' => [],
            'b' => ['b.a'],
            'b.a' => [],
        ];
        $result = [
            ['a', 'a.a', 'a.b'],
            ['b', 'b.a'],
        ];

        /**
         *
         */
        $lookup = [
            '' => ['a', 'b'],
            'a' => [],
            'b' => ['b.a'],
            'b.a' => ['b.a.a', 'b.a.b'],
            'b.a.a' => [],
            'b.a.b' => ['b.a.c'],
            'b.a.c' => []
        ];
        $result = [
            ['a', 'a.a', 'a.b'],
            ['b', 'b.a', ['b.a.a'], ['b.a.b', 'b.a.c']],
        ];

    }

}
