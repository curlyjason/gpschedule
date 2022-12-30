<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Item Entity
 *
 * @property int $id
 * @property int|null $flow_id
 * @property string|null $status
 * @property string|null $turnaround
 * @property int|null $quantity
 * @property string|null $press
 * @property string|null $item_code
 * @property string|null $item_description
 * @property \Cake\I18n\FrozenTime|null $due_date
 * @property string|null $customer_name
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Job[] $jobs
 */
class Item extends Entity
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
        'flow_id' => true,
        'status' => true,
        'turnaround' => true,
        'quantity' => true,
        'press' => true,
        'item_code' => true,
        'item_description' => true,
        'due_date' => true,
        'customer_name' => true,
        'created' => true,
        'modified' => true,
        'jobs' => true,
    ];
}
