<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StandardsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StandardsTable Test Case
 */
class StandardsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\StandardsTable
     */
    protected $Standards;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Standards',
        'app.Departments',
        'app.Processes',
        'app.Templates',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Standards') ? [] : ['className' => StandardsTable::class];
        $this->Standards = $this->getTableLocator()->get('Standards', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Standards);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\StandardsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\StandardsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
