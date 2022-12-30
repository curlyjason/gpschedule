<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Migration\AbstractMigration;

final class CreateDepartment extends AbstractMigration
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
        $departmentsTable = $this->table('departments');
        $departmentsTable
            ->addColumn('name', 'char', ['limit' => 255])
            ->create();
        $this->requiredCakeNormColumns($departmentsTable)
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
