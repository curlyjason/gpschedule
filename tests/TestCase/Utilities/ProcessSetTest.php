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

    public function test_construct()
    {
        $this->loadFixtureScenario('SingleStreamProcess');
        $processes = $this->getRecords('Processes');

        $SetManager = new ProcessSetDouble($processes);

        $this->assertInstanceOf(ProcessSet::class, $SetManager);

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
        $SetManager = new ProcessSetDouble([]);
        $SetManager->setKeyedByPrereq($prereqLookup);

        $actual = $SetManager->initIteratorSeed();

        $this->assertEquals($expectedResult, $actual);
    }

    public function test_createProcessWithSpecificId()
    {
        $job = JobFactory::make()->persist();
        ProcessThread::generate($job);
        ProcessThread::generate($job, 6, 12, 3);
        ProcessThread::generate($job, 13, 15, 2);
        ProcessThread::generate($job, 16,20, 10);
        ProcessThread::generate($job, 21,25,'');
        ProcessThread::generate($job, 26,29,21);

        $processSet = new ProcessSetDouble($this->getRecords('Processes'));

        debug($processSet);
    }

    public function test_getIterator()
    {
        $this->loadFixtureScenario('SingleStreamProcess');
        $processes = $this->getRecords('Processes'); //straight line schedule

        $iterator = (new ProcessSetDouble($processes))->getIteratorSeedIterator();

        $this->assertInstanceOf(\RecursiveArrayIterator::class, $iterator);

    }

    public function test_longestStepCount()
    {
        $job = JobFactory::make()->persist();

        ProcessThread::generate($job);
        ProcessThread::generate($job, 6, 12, 3);
        ProcessThread::generate($job, 13, 15, 2);
        ProcessThread::generate($job, 16,20, 10);
        ProcessThread::generate($job, 21,25,'');
        ProcessThread::generate($job, 26,29,21);
        ProcessThread::generate($job, 30,75,21);
        $b = new \OSDTImer();
        $b->start();
//        foreach (range(0,99) as $c) {
            $processSet = new ProcessSetDouble($this->getRecords('Processes'));
//        }
        debug($b->result());

        $output = $processSet->setThreadPaths();

        debug($processSet);
//        debug($processSet->getPrereqChain());
//        $processSet->initIteratorSeed();
//        debug($processSet->getIteratorSeed());
    }

    public function test_makeSingleStreamProcessSet()
    {
        $job = JobFactory::make()->persist();
        $this->loadFixtureScenario('StraightProcessThread', $job);
    }

    public function test_makeDualSingleStreamProcessSet()
    {
        $job = JobFactory::make()->persist();
        $this->loadFixtureScenario('TwoStraightProcessThread', $job);
    }

    public function test_makeSingleSplitProcessSet()
    {
        $job = JobFactory::make()->persist();
        $this->loadFixtureScenario('BranchedProcessThread', $job);
    }

    public function test_makeMixedProcessSet()
    {
        $job = JobFactory::make()->persist();
        $this->loadFixtureScenario('BranchedAndStraightProcessThread', $job);
    }

}
