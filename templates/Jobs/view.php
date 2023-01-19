<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Job $job
 * @var \App\Utilities\ProcessSet $processSet
 */

use App\Model\Entity\Process;

//$processes = $processSet->getProcesses();
$processes = collection ($processSet->getProcesses())
    ->map(function(Process $process){
        unset($process->modified, $process->created);
        $process->start_date = $process->start_date->format('Y-m-d');
        return $process->toArray();
    })->toArray();

echo "Thread Paths";
pre($processSet->getThreadPaths());
echo "Processes";
pre($processes);
?>
