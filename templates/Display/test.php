<?php

use App\View\AppView;

/**
 * @var AppView $this
 * @var RecursiveArrayIterator $data
 */

debug($data);
echo '<table>';
While($data->valid()){
    echo '<tr>';
    echo '<td>';
    if($data->hasChildren()){
        debug($data->current());
        echo 'array';
    }
    else {
        echo $data->current();
    }
    echo '</td>';
    echo '</tr>';
    $data->next();
}
echo '</table>';

echo '<table>';
debug($data->getArrayCopy());
//echo $this->Html->tableCells($data->getArrayCopy());
echo '</table>';
?>

