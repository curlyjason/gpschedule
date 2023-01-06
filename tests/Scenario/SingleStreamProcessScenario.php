<?php

namespace App\Test\Scenario;

use App\Model\Entity\Job;
use App\Model\Entity\Process;
use App\Test\Factory\JobFactory;
use App\Test\Factory\ProcessFactory;
use App\Test\Traits\RetrievalTrait;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
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

        $processes = $this->mixUpSequence($processes);

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

    /**
     * @param ResultSetInterface|array|Process|EntityInterface $processes
     * @return array
     */
    private function mixUpSequence(array $processes): array
    {
        $processes = collection($processes)->shuffle()->toArray();
        $prereqId = null;
        $processes = collection($processes)
            ->map(function ($process) use (&$prereqId) {
                $process->set('prereq', $prereqId);
                $prereqId = $process->id;
                return $process;
            })
            ->toArray();

        return $processes;
    }
}
