<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Job $job
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Job'), ['action' => 'edit', $job->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Job'), ['action' => 'delete', $job->id], ['confirm' => __('Are you sure you want to delete # {0}?', $job->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Jobs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Job'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="jobs view content">
            <h3><?= h($job->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Item') ?></th>
                    <td><?= $job->has('item') ? $this->Html->link($job->item->id, ['controller' => 'Items', 'action' => 'view', $job->item->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Job Number') ?></th>
                    <td><?= h($job->job_number) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($job->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Due Date') ?></th>
                    <td><?= h($job->due_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($job->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($job->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Processes') ?></h4>
                <?php if (!empty($job->processes)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Department Id') ?></th>
                            <th><?= __('Standard Id') ?></th>
                            <th><?= __('Process Code') ?></th>
                            <th><?= __('Start Date') ?></th>
                            <th><?= __('Duration') ?></th>
                            <th><?= __('Prereq') ?></th>
                            <th><?= __('Department Priority') ?></th>
                            <th><?= __('Complete') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Job Id') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($job->processes as $processes) : ?>
                        <tr>
                            <td><?= h($processes->id) ?></td>
                            <td><?= h($processes->department_id) ?></td>
                            <td><?= h($processes->standard_id) ?></td>
                            <td><?= h($processes->process_code) ?></td>
                            <td><?= h($processes->start_date) ?></td>
                            <td><?= h($processes->duration) ?></td>
                            <td><?= h($processes->prereq) ?></td>
                            <td><?= h($processes->department_priority) ?></td>
                            <td><?= h($processes->complete) ?></td>
                            <td><?= h($processes->name) ?></td>
                            <td><?= h($processes->job_id) ?></td>
                            <td><?= h($processes->created) ?></td>
                            <td><?= h($processes->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Processes', 'action' => 'view', $processes->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Processes', 'action' => 'edit', $processes->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Processes', 'action' => 'delete', $processes->id], ['confirm' => __('Are you sure you want to delete # {0}?', $processes->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
