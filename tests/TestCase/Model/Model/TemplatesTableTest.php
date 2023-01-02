<?php

namespace App\Test\TestCase\Model\Model;

use App\Test\Factory\TemplateFactory;
use App\Test\Traits\RetrievalTrait;

class TemplatesTableTest extends \Cake\TestSuite\TestCase
{

    use RetrievalTrait;

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

    public function test_getAutomaticallyContainsStandards()
    {
        $factory = TemplateFactory::make()
            ->listeningToModelEvents(['Model.beforeFind']);
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

}
