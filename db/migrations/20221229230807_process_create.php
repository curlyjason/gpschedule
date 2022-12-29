<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class ProcessCreate extends AbstractMigration
{
    use PhinxHelperTrait;

    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $processTable = $this->table('processes');
        $this->requiredCakeNormColumns($processTable)
            ->addColumn('start_date', 'datetime', [])
            ->addColumn('duration', 'integer', ['comment' => 'in minutes'])
            ->addColumn('sequence', 'integer', ['comment' => 'order inside job'])
            ->addColumn('department_priority', 'integer', ['comment' => 'order of priority inside department'])
            ->addColumn('complete', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => 0])
            ->addColumn('name', 'char', ['limit' => 255])
            ->create();
    }
}
