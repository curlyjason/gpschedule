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
        debug($SetManager);
        $result = $SetManager->childIteratorSeed($SetManager->getFollowersOf(''));
        debug($result);
    }

    public function process_sorter_provider()
    {
        return [
//            [
//                /**
//                 *two single starts with no following steps
//                 */
//                'prereqLookup' => [
//                    '' => ['a', 'b'],
//                    'a' => [],
//                    'b' => [],
//                ],
//                'expectedResult' => [
//                    ['a'],
//                    ['b'],
//                ]
//            ],
//            [
//                /**
//                 *single starts with no following steps
//                 */
//                'prereqLookup' => [
//                    '' => ['a'],
//                    'a' => [],
//                ],
//                'expectedResult' => [
//                    'a',
//                ]
//            ],
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
//            [
//                /**
//                 *single split
//                 */
//                'prereqLookup' => [
//                    '' => ['a', 'b'],
//                    'a' => ['a.a'],
//                    'a.a' => ['a.b'],
//                    'a.b' => [],
//                    'b' => ['b.a'],
//                    'b.a' => [],
//                ],
//                'expectedResult' => [
//                    ['a', 'a.a', 'a.b'],
//                    ['b', 'b.a'],
//                ]
//            ],
//            [
//                /**
//                 *double split
//                 */
//                'prereqLookup' => [
//                    '' => ['a', 'b'],
//                    'a' => [],
//                    'b' => ['b.a'],
//                    'b.a' => ['b.a.a', 'b.a.b'],
//                    'b.a.a' => [],
//                    'b.a.b' => ['b.a.c'],
//                    'b.a.c' => []
//                ],
//                'expectedResult' => [
//                    ['a', 'a.a', 'a.b'],
//                    ['b', 'b.a', ['b.a.a'], ['b.a.b', 'b.a.c']],
//                ]
//            ],
        ];
    }

}
