<?php

namespace App\Test\Utilities;

use App\Model\Entity\Job;
use App\Test\Factory\ProcessFactory;

class ProcessThread
{

    public static function generate(Job $job, int $start = 101, int $end = 110, $prereq = '')
    {
        $rounds = range($start, $end);
        collection($rounds)
            ->map(function($step) use ($job, &$prereq, $start){
                $prereq = $step == $start ? $prereq : $step-1;
                $date = $step == $start ? time() : time() + ($step * DAY);
                ProcessFactory::make([
                    'id' => $step,
                    'prereq' => $prereq,
                    'start_date' => $date,
                    'job_id' => $job->id,
                    'duration' => 5
                ])->persist();
            })->toArray();
    }


}
