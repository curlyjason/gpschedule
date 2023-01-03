<?php

namespace App\Test\Scenario;

use App\Test\Factory\StandardFactory;
use App\Test\Factory\StandardsTemplateFactory;
use App\Test\Factory\TemplateFactory;
use App\Test\Traits\RetrievalTrait;
use Cake\ORM\Entity;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;

class MixedSequenceTemplateScenario implements FixtureScenarioInterface
{

    use RetrievalTrait;

    /**
     *
     */
    public function load($n = 1, ...$args)
    {
        $template = TemplateFactory::make()->persist();
        $standards = StandardFactory::make(5)->persist();
        $sequence = [4,5,1,3,2];
        $joinData = collection($standards)
            ->map(function($standard, $index) use ($template, $sequence) {
                return [
                    'standard_id' => $standard->id,
                    'template_id' => $template->id,
                    'sequence' => $sequence[$index],
                ];
            })
            ->toArray();
        StandardsTemplateFactory::make($joinData)->persist();
        $result = $this->getRecords('Templates');

        return count($result) === 1 ? $result[0] : $result;

    }

}
