<?php

namespace App\Test\Scenario;

use App\Model\Entity\Job;
use App\Test\Factory\JobFactory;
use App\Test\Factory\ProcessFactory;
use App\Test\Traits\RetrievalTrait;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use mysql_xdevapi\Collection;
use function PHPUnit\Framework\isEmpty;

class SingleStreamProcessScenario implements FixtureScenarioInterface
{

    use RetrievalTrait;

    /**
     * Load method requires a job object, and will load one if
     * one is not provided
     * @inheritDoc
     */
    public function load(...$args)
    {
        $args = (func_get_args());
        if(isEmpty($args) || ! $args[0] instanceof Job){
            $job = JobFactory::make()->persist();
        } else {
            $job = $args[0];
        }
        $processes = ProcessFactory::make($this->basicProcessArray($job))->persist();

        $process_ids = collection($processes)->extract('id')->shuffle()->toList();

        $processes = collection($processes)->map(function($process, $index) use (&$process_ids){
            if($index === 0) {
                return $process;
            }
            while($process->id === $process_ids[0]){
                $process_ids = collection($process_ids)->shuffle()->toList();
            }
            $process->set('prereq', array_shift($process_ids));
            return $process;
        })->toArray();

        $table = ProcessFactory::make()->getTable();
        $patch = collection($processes)->map(function($process) use($table) {
            $patch = [
                'id' => $process->id,
                'prereq' => $process->prereq
            ];
            $table->patchEntity($process, $patch);
            $table->save($process);
        })->toArray();

    }

    private function basicProcessArray(Job $job, $count = 5)
    {
        return collection(range(0, $count))->map(function($processNumber) use ($job){
            return ['job_id' => $job->id];
        })->toArray();
    }
}
