<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Job $job
 * @var \Cake\Collection\CollectionInterface|string[] $items
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Jobs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="jobs form content">
            <?= $this->Form->create($job) ?>
            <fieldset>
                <legend><?= __('Add Job') ?></legend>
                <?php
                    echo $this->Form->control('item_id', ['options' => $items]);
                    echo $this->Form->control('due_date', ['empty' => true]);
                    echo $this->Form->control('job_number');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
