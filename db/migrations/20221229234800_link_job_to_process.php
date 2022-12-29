<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class LinkJobToProcess extends AbstractMigration
{
    use \App\Utilities\Phinx\PhinxHelperTrait;

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
        $table = $this->table('processes');
        $this->requiredForeignKey($table, 'jobs');
    }
}
