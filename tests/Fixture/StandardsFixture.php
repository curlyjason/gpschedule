<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StandardsFixture
 */
class StandardsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'department_id' => 1,
                'process_code' => '',
                'name' => '',
                'uom' => '',
                'units_per_hour' => 1,
                'daily_capacity' => 1,
                'created' => '2022-12-30 22:20:48',
                'modified' => '2022-12-30 22:20:48',
            ],
        ];
        parent::init();
    }
}
