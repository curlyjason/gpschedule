<?php

namespace App\Utilities;

use App\Model\Entity\Process;
use Cake\Utility\Hash;

class ProcessSet
{
    private array $keydById;
    protected array $keyedByPrereq;
    private ?array $iteratorSeed = null;

    /**
     * @param Process[] $processes
     */
    public function __construct(array $processes)
    {
        $this->keydById = collection($processes)
            ->indexBy(function($process) {
                return $process->id;
            })
            ->toArray();
        $this->buildFollowerLookup();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getPrereqOf($key)
    {
        return $this->keydById[$key]->prereq;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getFollowersOf($key)
    {
        return $this->keyedByPrereq[$key] ?? [];
    }

    public function getProcess($key)
    {
        return $this->keydById[$key] ?? null;
    }

    public function getChildIterator()
    {
        $this->iteratorSeed = $this->iteratorSeed ?? $this->childIteratorSeed($this->getFollowersOf(''));
        return new \RecursiveArrayIterator($this->childIteratorSeed($this->iteratorSeed));
    }

    private function getInitialProcessesKeys()
    {
        return $this->getFollowersOf('');
    }

    private function buildFollowerLookup()
    {
        $keys = array_keys($this->keydById);
        $this->keyedByPrereq = array_fill_keys($keys, []);
        collection($keys)
            ->each(function($key) {
                $this->keyedByPrereq[$this->getPrereqOf($key)][] = $key;
            })
            ->toArray();
    }

    protected function childIteratorSeed($followers)
    {
        debug($followers);
            collection ($followers)->map(function($follower){
                debug($follower);
                $this->iteratorSeed[] = $follower;
                $this->childIteratorSeed($this->getFollowersOf($follower));
            })->toArray();

        return $this->iteratorSeed;
    }

}
