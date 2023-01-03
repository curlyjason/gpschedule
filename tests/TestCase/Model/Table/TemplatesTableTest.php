<?php

namespace App\Test\TestCase\Model\Table;

use App\Test\Factory\DepartmentFactory;
use App\Test\Factory\StandardsTemplateFactory;
use App\Test\Factory\TemplateFactory;
use App\Test\Traits\RetrievalTrait;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;

class TemplatesTableTest extends TestCase
{

    use RetrievalTrait;
    use ScenarioAwareTrait;

    public function test_findAutomaticallyContainsStandards()
    {
        $factory = TemplateFactory::make()
            /*->listeningToModelEvents(['Model.beforeFind'])*/;
        $persisted = $factory
            ->withStandards(5)
            ->persist();

        $template = $factory->getTable()
            ->find()
            ->where(['id' => $persisted->id])
            ->toArray();

        $this->assertCount(
            count($this->getRecords('Standards')),
            $template[0]->standards
        );
    }

    public function test_getAutomaticallyContainsStandards()
    {
        $factory = TemplateFactory::make()
            /*->listeningToModelEvents(['Model.beforeFind'])*/;
        $persisted = $factory
            ->withStandards(5)
            ->persist();

        $template = $factory->getTable()
            ->get($persisted->id);

        $this->assertCount(
            count($this->getRecords('Standards')),
            $template->standards
        );
    }

    public function test_saveStandardsOnTemplateWithSequence()
    {
        $department = DepartmentFactory::make()->persist();
        $patch = [
            'name' => 'template name',
            'standards' => [
                (int) 0 => [
                    'process_code' => '1234',
                    'name' => 'prepress',
                    'uom' => 'min',
                    'units_per_hour' => (float) 60,
                    'daily_capacity' => 480,
                    '_joinData' => [
                        'sequence' => 0
                    ],
                    'department_id' => $department->id
                ],
                (int) 1 => [
                    'process_code' => '1234',
                    'name' => 'prepress',
                    'uom' => 'min',
                    'units_per_hour' => (float) 60,
                    'daily_capacity' => 480,
                    '_joinData' => [
                        'sequence' => 1
                    ],
                    'department_id' => $department->id
                ]
            ]
        ];

        $Template = TemplateFactory::make()->getTable();
        $entity = $Template->newEntity($patch);
        $Template->save($entity);

        $records = $this->getRecords('Templates');

        $this->assertCount(1, $records);
        $this->assertEquals(1, $records[0]->standards[1]->_joinData->sequence);
        $this->assertEquals(0, $records[0]->standards[0]->_joinData->sequence);

    }

    public function test_containedStandardsAreInProperOrder()
    {
        $template = $this->loadFixtureScenario('MixedSequenceTemplate');

        collection($template->standards)->map(function($standard, $index){
            $this->assertEquals($index+1, $standard->sequence);
        })->toArray();
    }
}

