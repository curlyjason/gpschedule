<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StandardsTemplatesFixture
 */
class StandardsTemplatesFixture extends TestFixture
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
                'standard_id' => 1,
                'template_id' => 1,
            ],
        ];
        parent::init();
    }
}
