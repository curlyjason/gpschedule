<?php

namespace App\Test\TestCase\Utilities;

use App\Model\Entity\Job;
use App\Test\Factory\JobFactory;
use App\Test\Factory\ProcessFactory;
use App\Test\TestDoubles\ProcessSetDouble;
use App\Test\Traits\RetrievalTrait;
use App\Test\Utilities\DebugTrait;
use App\Test\Utilities\ProcessThread;
use App\Utilities\ProcessSet;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;

class ProcessSetTest extends TestCase
{
    use ScenarioAwareTrait;
    use RetrievalTrait;
    use IntegrationTestTrait;
    use DebugTrait;

    public function threadScenarioProvider()
    {
        return [
            [
                'StraightProcessThread',
                [
                    'thread_count' => 1,
                    'thread_ends' => [110],
                    'thread_duration' => [50],
                    'grid_dimensions' => ['threads' => 1, 'steps' => 10],
                    'max_duration' => 50,
                ]
            ],
            [
                'TwoStraightProcessThread',
                [
                    'thread_count' => 2,
                    'thread_ends' => [110,120],
                    'thread_duration' => [50, 50],
                    'grid_dimensions' => ['threads' => 2, 'steps' => 10],
                    'max_duration' => 50,
                ]
            ],
            [
                'BranchedProcessThread',
                [
                    'thread_count' => 3,
                    'thread_ends' => [110,120, 130],
                    'thread_duration' => [50, 75, 100],
                    'grid_dimensions' => ['threads' => 3, 'steps' => 20],
                    'max_duration' => 100,
                ]
            ],
            [
                'BranchedAndStraightProcessThread',
                [
                    'thread_count' => 4,
                    'thread_ends' => [110,120,130,140],
                    'thread_duration' => [50, 75, 100, 50],
                    'grid_dimensions' => ['threads' => 4, 'steps' => 20],
                    'max_duration' => 100,
                ]
            ],
        ];
    }

    public function test_allPropertiesGetNewValuesAfterConstruct()
    {
        $processSet = $this->makeSetForScenario('StraightProcessThread');
        $original_value = $processSet->getDefaultPropertyValues();
        $current_value = $processSet->getCurrentPropertyValues();

        foreach (array_keys($original_value) as $key) {
            $this->assertNotEquals($current_value, $original_value,
                "The property $key was not set during _construct");
        }

    }

    /**
     * @dataProvider threadScenarioProvider
     * @param string $scenario
     * @param array $expected
     * @return void
     */
    public function test_getterValuesAfterConstruct(string $scenario, array $expected)
    {
        $processSet = $this->makeSetForScenario($scenario);
        $this->assertEquals($expected['thread_count'], $processSet->getThreadCount(), "Unexpected thread count for $scenario");
        $this->assertEquals($expected['thread_ends'], $processSet->getThreadEnds(), "Unexpected thread end ids for $scenario");
        $this->assertThreadDuration($processSet, $expected, "Unexpected thread durations for $scenario");
        $this->assertEquals($expected['grid_dimensions'], $processSet->getGridDimensions(), "Unexpected grid dimensions for $scenario");
        $this->assertEquals($expected['max_duration'], $processSet->getMaxDuration(), "Unexpected max duration for $scenario");
    }

    /**
     * @dataProvider threadScenarioProvider
     * @param string $scenario
     * @param array $expected
     * @return void
     */
    public function test_Development(string $scenario, array $expected)
    {
        $processSet = $this->makeSetForScenario($scenario);
        debug($processSet->getThreadPaths());

        $this->assertEquals(1,1);

        debug($processSet->pathSegment($processSet::BEFORE, 104));
        debug($processSet->pathSegment($processSet::AFTER, 104));
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

    /**
     * @param $prereqLookup
     * @param $expectedResult
     * @dataProvider process_sorter_provider
     * @return void
     */
    public function test_process_sorter($prereqLookup, $expectedResult)
    {
        $job = new Job([]);
        $SetManager = new ProcessSetDouble([], $job);
        $SetManager->setKeyedByPrereq($prereqLookup);

        $actual = $SetManager->initIteratorSeed();

        $this->assertEquals($expectedResult, $actual);
    }

    public function test_getIterator()
    {
        $processSet = $this->makeSetForScenario('StraightProcessThread');
        $iterator = ($processSet->getTreeIterator());
        $this->assertInstanceOf(\RecursiveArrayIterator::class, $iterator);
    }

    /**
     * @param string $scenario
     * @return ProcessSetDouble
     */
    private function makeSetForScenario(string $scenario): ProcessSetDouble
    {
        $job = JobFactory::make()->persist();
        $this->loadFixtureScenario($scenario, $job);
        return new ProcessSetDouble($this->getRecords('Processes'), $job);
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

}
