<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class StandardsCreate extends AbstractMigration
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
        $standardsTable = $this->table('standards');
        $standardsTable
            ->addColumn('process_code', 'char', ['limit' => 255])
            ->addColumn('name', 'char', ['limit' => 255])
            ->addColumn('uom', 'char', ['limit' => 255])
            ->addColumn('units_per_hour', 'float', ['default' => 0])
            ->addColumn('daily_capacity', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'comment' => 'in minutes'])
            ->create();
        $this->requiredCakeNormColumns($standardsTable)
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
