<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Job $job
 * @var string[]|\Cake\Collection\CollectionInterface $items
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $job->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $job->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Jobs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="jobs form content">
            <?= $this->Form->create($job) ?>
            <fieldset>
                <legend><?= __('Edit Job') ?></legend>
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
