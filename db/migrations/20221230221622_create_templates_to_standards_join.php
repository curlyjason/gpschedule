<?php
declare(strict_types=1);

use App\Utilities\Phinx\PhinxHelperTrait;
use Phinx\Migration\AbstractMigration;

final class CreateTemplatesToStandardsJoin extends AbstractMigration
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
        $table = $this->table('standards_templates');
        $table->create();
        $this->requiredForeignKey($table, 'standards')->update();
        $this->requiredForeignKey($table, 'templates')->update();
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
