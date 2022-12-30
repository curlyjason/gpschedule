<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ItemsFixture
 */
class ItemsFixture extends TestFixture
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
                'flow_id' => 1,
                'status' => '',
                'turnaround' => '',
                'quantity' => 1,
                'press' => '',
                'item_code' => '',
                'item_description' => '',
                'due_date' => '2022-12-30 22:20:48',
                'customer_name' => '',
                'created' => '2022-12-30 22:20:48',
                'modified' => '2022-12-30 22:20:48',
            ],
        ];
        parent::init();
    }
}
