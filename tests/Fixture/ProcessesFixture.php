<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProcessesFixture
 */
class ProcessesFixture extends TestFixture
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
                'standard_id' => 1,
                'process_code' => '',
                'start_date' => '2022-12-30 22:20:48',
                'duration' => 1,
                'sequence' => 1,
                'department_priority' => 1,
                'complete' => 1,
                'name' => '',
                'job_id' => 1,
                'created' => '2022-12-30 22:20:48',
                'modified' => '2022-12-30 22:20:48',
            ],
        ];
        parent::init();
    }
}
