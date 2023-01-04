<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Process Entity
 *
 * @property int $id
 * @property int $department_id
 * @property int $standard_id
 * @property string|null $process_code
 * @property \Cake\I18n\FrozenTime|null $start_date
 * @property int|null $duration
 * @property int|null $prereq
 * @property int|null $department_priority
 * @property int|null $complete
 * @property string|null $name
 * @property int $job_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Department $department
 * @property \App\Model\Entity\Standard $standard
 * @property \App\Model\Entity\Job $job
 */
class Process extends Entity
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
        'standard_id' => true,
        'process_code' => true,
        'start_date' => true,
        'duration' => true,
        'prereq' => true,
        'department_priority' => true,
        'complete' => true,
        'name' => true,
        'job_id' => true,
        'created' => true,
        'modified' => true,
        'department' => true,
        'standard' => true,
        'job' => true,
    ];
}
