<?php

namespace App\Utilities\Phinx;

use Cake\Utility\Inflector;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Db\Table;

trait PhinxHelperTrait
{

    public function requiredCakeNormColumns($table)
    {
        $table
            ->addColumn(
                'created',
                'datetime',
                ['default' => null, 'null' => true,]
            )
            ->addColumn(
                'modified',
                'datetime',
                ['default' => null, 'null' => true,]
            );
            return $table;
    }

    /**
     * @param $table Table
     * @param $foreign_table_name string
     * @return void
     */
    public function requiredForeignKey($table, $foreign_table_name)
    {
        $columnName = Inflector::singularize($foreign_table_name) . "_id";
        $table
            ->addColumn(
                $columnName,
                'integer',
                [
                    'limit' => MysqlAdapter::INT_REGULAR,
                    'null' => false,
                    'signed' => false,
                ]
            )
            ->addForeignKey(
                $columnName,
                $foreign_table_name,
                'id',
                ['delete' => 'CASCADE',])
            ->update();

    }

}
