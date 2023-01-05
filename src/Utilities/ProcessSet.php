<?php

namespace App\Utilities;

use App\Model\Entity\Process;
use Cake\Utility\Hash;

class ProcessSet
{
    private array $keydById;
    protected array $keyedByPrereq;
    protected array $iteratorSeed = [];

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

    protected function childIteratorSeed($followers, $path = '0')
    {
        debug($followers);

        collection ($followers)->map(function($follower, $index) use ($path, $followers){
            debug($follower);
            if(count($followers) > 1){
                $path .= ".$index";
            }
            debug($path);
            $this->iteratorSeed = Hash::insert($this->iteratorSeed, (string) $path, $follower);
            $path = $this->incrementPath($path);
            $this->childIteratorSeed($this->getFollowersOf($follower), $path);
        })->toArray();

        return $this->iteratorSeed;
    }

    /**
     * @param string $path
     * @return string
     */
    private function incrementPath(string $path)
    {
        $pathArray = explode('.', $path);
        $last = array_pop($pathArray);
        $pathArray[] = ++$last;
        return implode('.', $pathArray);
    }



}
