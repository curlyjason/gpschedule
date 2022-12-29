<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class JobCreate extends AbstractMigration
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
        $processTable = $this->table('jobs');
        $this->requiredCakeNormColumns($processTable)
            ->addColumn('due_date', 'datetime', [])
            ->addColumn('job_number', 'char', ['limit' => 255])
            ->create();
    }
}
