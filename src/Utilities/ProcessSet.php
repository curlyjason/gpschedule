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
        $this->iteratorSeed = empty($this->iteratorSeed)
            ? $this->childIteratorSeed($this->getFollowersOf(''))
            : $this->iteratorSeed;
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
        $split = count($followers) > 1;

        collection ($followers)->map(function($follower, $index) use ($path, $split){

            $path = $split ? $this->split($path, $index) : $path ;
            $this->iteratorSeed =
                Hash::insert($this->iteratorSeed, $path, $follower);
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

    private function split(mixed $path, $index)
    {
        $pathArray = explode('.', $path);
        $last = array_pop($pathArray);
        $pathArray[] = ($last + $index) . '.0';
        return implode('.', $pathArray);
    }
    
}
