<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StandardsTemplate Entity
 *
 * @property int $id
 * @property int $standard_id
 * @property int $template_id
 *
 * @property \App\Model\Entity\Standard $standard
 * @property \App\Model\Entity\Template $template
 */
class StandardsTemplate extends Entity
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
        'standard_id' => true,
        'template_id' => true,
        'standard' => true,
        'template' => true,
    ];
}
