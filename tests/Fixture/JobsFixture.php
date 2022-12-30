<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * JobsFixture
 */
class JobsFixture extends TestFixture
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
                'item_id' => 1,
                'due_date' => '2022-12-30 22:20:48',
                'job_number' => '',
                'created' => '2022-12-30 22:20:48',
                'modified' => '2022-12-30 22:20:48',
            ],
        ];
        parent::init();
    }
}
