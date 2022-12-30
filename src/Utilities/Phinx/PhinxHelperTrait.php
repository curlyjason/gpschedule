<?php

namespace App\Utilities\Phinx;

use Cake\Utility\Inflector;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Db\Table;

trait PhinxHelperTrait
{

    /**
     * Make 'created' and 'modified' for the table
     *
     * @param Table $table
     * @return Table
     */
    public function requiredCakeNormColumns(Table $table): Table
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
     * Make a link field and make it a required foreign-key
     *
     * @param Table $table
     * @param string $foreign_table_name
     * @param ?string $after
     * @return Table $table
     */
    public function requiredForeignKey(Table $table, string $foreign_table_name, string $after = null): Table
    {
        $options = [
            'limit' => MysqlAdapter::INT_REGULAR,
            'null' => false,
            'signed' => false,
        ];
        if (!is_null($after)) {
            $options['after'] = $after;
        }
        $columnName = Inflector::singularize($foreign_table_name) . "_id";

        $table
            ->addColumn(
                $columnName,
                'integer',
                $options
            )
            ->addForeignKey(
                $columnName,
                $foreign_table_name,
                'id',
                ['delete' => 'CASCADE',])
            ->update();

        return $table;
    }

}
