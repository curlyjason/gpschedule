<?php

namespace App\Utilities;

use App\Model\Entity\Process;
use Cake\Utility\Hash;
use RecursiveArrayIterator;

class ProcessSet
{
    private array $keydById;
    protected array $keyedByPrereq;
    protected array $iteratorSeed = [];
    protected array $prereqChain = [];

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
     * Get the id of the prerequisite Process
     *
     * @param int $key id of a process
     * @return ?string
     */
    public function getPrereqOf($key): ?string
    {
        return $this->keydById[$key]->prereq;
    }

    /**
     * Get an array of the ids following Processes
     *
     * @param int $key
     * @return array
     */
    public function getFollowersOf($key): array
    {
        return $this->keyedByPrereq[$key] ?? [];
    }

    /**
     * Get a stored Process by id
     *
     * @param int $key Process id
     * @return ?Process
     */
    public function getProcess($key)
    {
        return $this->keydById[$key] ?? null;
    }

    public function getThreadCount()
    {
        return collection($this->keyedByPrereq)
            ->reduce(function($accum, $node) {
                return $accum += empty($node);
            }, 0);
    }

    public function getLongestThread()
    {
        return $this->getIterator()->count();
    }

    /**
     * Return a fresh iterator
     *
     * @return RecursiveArrayIterator
     */
    public function getIterator(): RecursiveArrayIterator
    {
        $this->insureSeedInitialization();
        return new RecursiveArrayIterator($this->iteratorSeed);
    }

    private function getInitialProcessesKeys(): array
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

    protected function initIteratorSeed($followers = null, $path = '0'): array
    {
        $followers = $followers ?? $this->getInitialProcessesKeys();
        $split = count($followers) > 1;

        collection ($followers)->map(function($follower, $index) use ($path, $split){

            $path = $split ? $this->splitPath($path, $index) : $path ;
            $this->iteratorSeed =
                Hash::insert($this->iteratorSeed, $path, $follower);
            $path = $this->incrementPath($path);

            $this->initIteratorSeed($this->getFollowersOf($follower), $path);
        })->toArray();

        return $this->iteratorSeed;
    }

    /**
     * @param string $path
     * @return string
     */
    private function incrementPath(string $path): string
    {
        $callable = function($last) {return ++$last;};
        return $this->modifyLastPathEntry($path,$callable);
    }

    private function splitPath(mixed $path, $index): string
    {
        $callable = function($last) use ($index ){return ($last + $index) . '.0';};
        return $this->modifyLastPathEntry($path,$callable);
    }

    private function modifyLastPathEntry($path, $callable) {
        $pathArray = explode('.', $path);
        $last = array_pop($pathArray);
        $pathArray[] = $callable($last);
        return implode('.', $pathArray);
    }

    /**
     * @return void
     */
    private function insureSeedInitialization(): void
    {
        if (empty($this->iteratorSeed)) {
            $this->initIteratorSeed($this->getFollowersOf(''));
        };
    }

    public function longestStepCount($interatorSeed = null, $prereqChain = '')
    {
//        $followers = $followers ?? $this->getInitialProcessesKeys();
//        debug($followers);
//        $split = count($followers) > 1;
//
//        $output = collection($followers)->map(function($follower, $index) use ($split, $prereqChain){
//            $prereqChain .= ".$follower";
//            $this->longestStepCount($this->getFollowersOf($follower), $prereqChain);
//            debug($prereqChain);
//            $this->prereqChain[$index] = $prereqChain;
//            return $prereqChain;
//        })->toArray();
//        debug ($output);
//        return $output;
        $this->initIteratorSeed();
        $interatorSeed = $interatorSeed ?? $this->iteratorSeed;
        return collection($this->iteratorSeed)
            ->map(function($layer, $index){
                if(is_array($layer)){
                    $this->longestStepCount($layer);
                }
                else {
                    $this->prereqChain[$index] = $this->prereqChain[$index]+1;
                }
            })->toArray();
    }
}

