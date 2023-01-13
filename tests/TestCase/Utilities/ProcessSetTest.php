<?php

namespace App\Test\TestCase\Utilities;

use App\Test\Factory\JobFactory;
use App\Test\Factory\ProcessFactory;
use App\Test\TestDoubles\ProcessSetDouble;
use App\Test\Traits\RetrievalTrait;
use App\Test\Utilities\ProcessThread;
use App\Utilities\ProcessSet;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;

class ProcessSetTest extends TestCase
{
    use ScenarioAwareTrait;
    use RetrievalTrait;

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

    public function test_getIterator()
    {
        $processSet = $this->makeSetForScenario('StraightProcessThread');
        $iterator = ($processSet->getIteratorSeedIterator());
        $this->assertInstanceOf(\RecursiveArrayIterator::class, $iterator);
    }

    /**
     * @dataProvider threadScenarioProvider
     * @param string $scenario
     * @param array $expected
     * @return void
     */
    public function test_threadProperties(string $scenario, array $expected)
    {
        $processSet = $this->makeSetForScenario($scenario);
        $this->assertEquals($expected['thread_count'], $processSet->getThreadCount(), "Unexpected thread count for $scenario");
        $this->assertEquals($expected['thread_ends'], $processSet->getThreadEnds(), "Unexpected thread end ids for $scenario");
        $this->assertThreadDuration($processSet, $expected, "Unexpected thread durations for $scenario");
    }

    /**
     * @param string $scenario
     * @return ProcessSetDouble
     */
    private function makeSetForScenario(string $scenario): ProcessSetDouble
    {
        $job = JobFactory::make()->persist();
        $this->loadFixtureScenario($scenario, $job);
        return new ProcessSetDouble($this->getRecords('Processes'));
    }

    /**
     * @param ProcessSetDouble $processSet
     * @param array $expected
     * @return void
     */
    private function assertThreadDuration(ProcessSetDouble $processSet, array $expected, $error): void
    {
        collection($processSet->getThreadEnds())
            ->map(function ($end_id, $index) use ($expected, $processSet, $error) {
                $this->assertEquals($expected['thread_duration'][$index], $processSet->getDuration($end_id), $error);
            })->toArray();
    }

    public function threadScenarioProvider()
    {
        return [
            [
                'StraightProcessThread',
                [
                    'thread_count' => 1,
                    'thread_ends' => [10],
                    'thread_duration' => [50]
                ]
            ],
            [
                'TwoStraightProcessThread',
                [
                    'thread_count' => 2,
                    'thread_ends' => [10,20],
                    'thread_duration' => [50, 50]
                ]
            ],
            [
                'BranchedProcessThread',
                [
                    'thread_count' => 3,
                    'thread_ends' => [10,20, 30],
                    'thread_duration' => [50, 75, 100]
                ]
            ],
            [
                'BranchedAndStraightProcessThread',
                [
                    'thread_count' => 4,
                    'thread_ends' => [10,20,30,40],
                    'thread_duration' => [50, 75, 100, 50]
                ]
            ],
        ];
    }

}
