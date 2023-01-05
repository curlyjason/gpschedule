<?php

namespace App\Utilities;

use App\Model\Entity\Process;

class ProcessSet
{
    private array $keydById;
    private array $keyedByPrereq;
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
        $this->childIteratorSeed();
        debug($this->keyedByPrereq);
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
        return $this->keyedByPrereq[$key]->prereq;
    }

    public function getProcess($key)
    {
        return $this->keydById[$key] ?? null;
    }

    public function getChildIterator()
    {
        return new \RecursiveArrayIterator($this->childIteratorSeed());
    }

    private function getInitialProcesses()
    {
        $starts = $this->getFollowersOf('');
        return collection($starts)
            ->map(function($start) {
                return $this->getProcess($start);
            })
            ->toArray();
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

    private function childIteratorSeed()
    {
        if (is_null($this->iteratorSeed)) {
            $this->iteratorSeed = collection($this->keyedByPrereq)
                ->reduce(function($accum, $followers, $prereq) {
                    if (count($followers) < 2) {
                        $accum[$prereq] = $this->getProcess($followers[0] ?? null);
                    }
                    else {
                        $accum[$prereq] = collection($followers)
                            ->map(function($follower) {
                                return $this->getProcess($follower);
                            })
                            ->toArray();
                    }
                }, []);
        }
        return $this->iteratorSeed;
    }

}
