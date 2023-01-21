<style>
    table, th, td {
        border: 1px solid black;
        text-align: center;
        padding: 0;
        margin: 3px;
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

$threads = $processSet->getThreadPaths($processSet::EXPLODED);
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

echo '<p> </p>';
echo '<p> </p>';
echo '<p> </p>';
echo '<p> 3</p>';
echo '<p> 3</p>';
echo '<p> 3</p>';
echo '<p> 3</p>';
