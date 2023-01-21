<style>
    table, th, td {
        border: 1px solid black;
    }
</style>


<?php
/**
 * @var AppView $this
 * @var Job $job
 * @var ProcessSet $processSet
 */

use App\Model\Entity\Job;
use App\Utilities\ProcessSet;
use App\View\AppView;

osd($processSet);
foreach ($processSet->getThreadPaths() as $index => $threadPath) {
    $threads[$index] = explode('.', $threadPath);
}
osd($threads);
$grid = $processSet->getGridDimensions();
$used = [];

/**
 * @param $process_id
 * @param ProcessSet $processSet
 * @return void
 */
$renderCell = function ($process_id) use (&$used, $processSet): void
{
    if (!in_array($process_id, $used)) {
        $cols = $processSet->threadCountAt($process_id) ?? 1;
        echo "<td colspan='$cols'>"
            . ($process_id ?? '')
            . "</td>";
        $used[] = $process_id;
    }

};

echo "<table>";
foreach (range(0, ($grid['steps']-1)) as $step_index) {
    echo "<tr>";
    foreach (range(0, $grid['threads']-1) as $thread_index) {
        if (!empty($threads[$thread_index][$step_index])) {
            $renderCell($threads[$thread_index][$step_index]);
        }
        else {
            echo "<td></td>";
        }
    }
    echo "</tr>";
}
echo "</table>";
