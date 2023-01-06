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
        $processes = $this->getRecords('Processes');

        $SetManager = new ProcessSetDouble($processes);

        $this->assertInstanceOf(ProcessSet::class, $SetManager);

    }

    /**
     * @param $prereqLookup
     * @param $expectedResult
     * @dataProvider process_sorter_provider
     * @return void
     */
    public function test_process_sorter($prereqLookup, $expectedResult)
    {
        $SetManager = new ProcessSetDouble([]);
        $SetManager->setKeyedByPrereq($prereqLookup);

        $actual = $SetManager->initIteratorSeed();

        $this->assertEquals($expectedResult, $actual);
    }

    public function process_sorter_provider()
    {
        return [
            [
                /**
                 *two single starts with no following steps
                 */
                'prereqLookup' => [
                    '' => ['a', 'b'],
                    'a' => [],
                    'b' => [],
                ],
                'expectedResult' => [
                    ['a'],
                    ['b'],
                ]
            ],
            [
                /**
                 *simple split
                 */
                'prereqLookup' => [
                    '' => ['a', 'b'],
                    'a' => ['c'],
                    'b' => ['d'],
                    'c' => [],
                    'd' => []
                ],
                'expectedResult' => [
                    ['a', 'c'],
                    ['b', 'd'],
                ]
            ],
            [
                /**
                 *single starts with no following steps
                 */
                'prereqLookup' => [
                    '' => ['a'],
                    'a' => [],
                ],
                'expectedResult' => [
                    'a',
                ]
            ],
            [
                /**
                 *straight line list
                 */
                'prereqLookup' => [
                    '' => ['a'],
                    'a' => ['a.b'],
                    'a.b' => ['b.c'],
                    'b.c' => []
                ],
                'expectedResult' => [
                    'a', 'a.b', 'b.c'
                ]
            ],
            [
                /**
                 *single split
                 */
                'prereqLookup' => [
                    '' => ['a', 'b'],
                    'a' => ['a.a'],
                    'a.a' => ['a.b'],
                    'a.b' => [],
                    'b' => ['b.a'],
                    'b.a' => [],
                ],
                'expectedResult' => [
                    ['a', 'a.a', 'a.b'],
                    ['b', 'b.a'],
                ]
            ],
            [
                /**
                 *double split
                 */
                'prereqLookup' => [
                    '' => ['a', 'b'],
                    'a' => ['a.a'],
                    'a.a' => ['a.b'],
                    'a.b' => [],
                    'b' => ['b.a'],
                    'b.a' => ['b.a.a', 'b.a.b'],
                    'b.a.a' => [],
                    'b.a.b' => ['b.a.c'],
                    'b.a.c' => []
                ],
                'expectedResult' => [
                    ['a', 'a.a', 'a.b'],
                    ['b', 'b.a', ['b.a.a'], ['b.a.b', 'b.a.c']],
                ]
            ],
        ];
    }

    public function test_getIterator()
    {
        $this->loadFixtureScenario('SingleStreamProcess');
        $processes = $this->getRecords('Processes'); //straight line schedule

        $iterator = (new ProcessSetDouble($processes))->getIterator();

        $this->assertInstanceOf(\RecursiveArrayIterator::class, $iterator);


    }

}
