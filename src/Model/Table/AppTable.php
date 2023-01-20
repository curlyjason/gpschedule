<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class AppTable extends Table
{

    public static function defaultConnectionName(): string {
        return 'test';
    }

}
