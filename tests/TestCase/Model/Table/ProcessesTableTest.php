<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProcessesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProcessesTable Test Case
 */
class ProcessesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProcessesTable
     */
    protected $Processes;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Processes',
        'app.Departments',
        'app.Standards',
        'app.Jobs',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Processes') ? [] : ['className' => ProcessesTable::class];
        $this->Processes = $this->getTableLocator()->get('Processes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Processes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ProcessesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\ProcessesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
