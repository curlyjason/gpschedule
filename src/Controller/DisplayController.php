<?php

namespace App\Controller;

class DisplayController extends AppController
{
    public function test()
    {
        $data = new \RecursiveArrayIterator([
        (int) 0 => (int) 1,
        (int) 1 => (int) 2,
        (int) 2 => (int) 3,
        (int) 3 => [
            (int) 0 => (int) 4,
            (int) 1 => (int) 5
        ],
        (int) 4 => [
            (int) 0 => (int) 6,
            (int) 1 => (int) 7,
            (int) 2 => (int) 8,
            (int) 3 => (int) 9,
            (int) 4 => (int) 10,
            (int) 5 => (int) 11,
            (int) 6 => (int) 12
        ]
    ]);

        $this->set(compact('data'));
    }
}
