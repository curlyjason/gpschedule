<?php

namespace App\Utilities\Phinx;

use Phinx\Db\Adapter\MysqlAdapter;

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
}
