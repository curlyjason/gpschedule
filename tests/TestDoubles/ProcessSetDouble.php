<?php

namespace App\Test\TestDoubles;

use App\Utilities\ProcessSet;

class ProcessSetDouble extends ProcessSet
{
    public function initIteratorSeed($followers = null, $path='0') : array
    {
        return parent::initIteratorSeed($followers, $path);
    }

    public function setKeyedByPrereq(array $array)
    {
        $this->keyedByPrereq = $array;
    }

}
