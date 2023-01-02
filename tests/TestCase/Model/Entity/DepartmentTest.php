<?php

namespace App\Test\TestCase\Model\Entity;

use App\Test\Factory\DepartmentFactory;
use App\Test\Traits\RetrievalTrait;
use Cake\TestSuite\TestCase;

class DepartmentTest extends TestCase
{
    use RetrievalTrait;

    public function test_sample()
    {
        DepartmentFactory::make(20)->persist();
        debug(count($this->getRecords('Departments')));
    }
}
