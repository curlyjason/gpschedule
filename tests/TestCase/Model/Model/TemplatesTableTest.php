<?php

namespace App\Test\TestCase\Model\Model;

use App\Test\Factory\TemplateFactory;
use App\Test\Traits\RetrievalTrait;

class TemplatesTableTest extends \Cake\TestSuite\TestCase
{

    use RetrievalTrait;
    //Should always return with Standards?
    //configure and test that basic config
    //returns containment on simple find or get

    public function test_findAutomaticallyContainsStandards()
    {
        $factory = TemplateFactory::make()
            ->listeningToModelEvents(['Model.beforeFind']);
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

}
