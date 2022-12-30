<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Job Entity
 *
 * @property int $id
 * @property int $item_id
 * @property \Cake\I18n\FrozenTime|null $due_date
 * @property string|null $job_number
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Item $item
 * @property \App\Model\Entity\Process[] $processes
 */
class Job extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'item_id' => true,
        'due_date' => true,
        'job_number' => true,
        'created' => true,
        'modified' => true,
        'item' => true,
        'processes' => true,
    ];
}
