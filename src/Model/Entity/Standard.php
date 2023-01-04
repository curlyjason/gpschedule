<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Standard Entity
 *
 * @property int $id
 * @property int $department_id
 * @property string|null $process_code
 * @property string|null $name
 * @property string|null $uom
 * @property float|null $units_per_hour
 * @property int|null $daily_capacity
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Department $department
 * @property \App\Model\Entity\Process[] $processes
 * @property \App\Model\Entity\Template[] $templates
 */
class Standard extends Entity
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
        'department_id' => true,
        'process_code' => true,
        'name' => true,
        'uom' => true,
        'units_per_hour' => true,
        'daily_capacity' => true,
        'created' => true,
        'modified' => true,
        'department' => true,
        'processes' => true,
        'templates' => true,
    ];

    public function _getPrereq()
    {
        return $this->_joinData->prereq ?? null;
    }
}
