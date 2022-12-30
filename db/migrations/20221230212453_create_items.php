<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateItems extends AbstractMigration
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
        $itemsTable = $this->table('items');
        $itemsTable
            ->addColumn('flow_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR])
            ->addColumn('status', 'char', ['limit' => 255])
            ->addColumn('turnaround', 'char', ['limit' => 255])
            ->addColumn('quantity', 'integer', ['limit' => MysqlAdapter::INT_REGULAR])
            ->addColumn('press', 'char', ['limit' => 255])
            ->addColumn('item_code', 'char', ['limit' => 255])
            ->addColumn('item_description', 'char', ['limit' => 255])
            ->addColumn('due_date', 'datetime', [])
            ->addColumn('customer_name', 'char', ['limit' => 255])
            ->create();
        $this->requiredCakeNormColumns($itemsTable)
            ->update();

    }

//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//
//    }

}
