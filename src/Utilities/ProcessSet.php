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
    protected array $threadEnds = [];
    protected $count = 0;
    protected $durationLookup = [];
    private $threadPaths;

    /**
     * @param Process[] $processes
     */
    public function __construct(array $processes)
    {
        $this->setKeyById($processes);
        $this->buildFollowerLookup();
        $this->setThreadEnds();
        $this->setThreadPaths();
        $this->registerDurations($this->threadPaths);
        $this->initIteratorSeed($this->getFollowersOf(''));
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
        return count($this->getThreadEnds());
    }

    public function getLongestThread()
    {
        return $this->getIteratorSeedIterator()->count();
    }

    protected function setThreadPaths()
    {
        $this->threadPaths = collection($this->getThreadEnds())
            ->reduce(function($accum, $endpoint, $index) {
                $path = $this->buildPathTo($endpoint);
                $accum[$index] = $path;
                return $accum;
            }, []);
    }

    public function getThreadPaths()
    {
        return $this->threadPaths;
    }

    /**
     * Return a fresh iterator
     *
     * @return RecursiveArrayIterator
     */
    public function getIteratorSeedIterator(): RecursiveArrayIterator
    {
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

    public function getThreadEnds()
    {
        return $this->threadEnds;
    }

    private function buildPathTo($endpoint)
    {
        $process = $this->getProcess($endpoint);
        $path = $endpoint;
        while ($process->prereq != null) {
            $path = "$process->prereq.$path";
            $process = $this->getProcess($process->prereq);
        }
        return $path;
    }

    private function registerDurations(mixed $path)
    {
        foreach($this->threadPaths as $path) {
            $total = 0;
            $a = explode('.', $path);
            sort($a);
            foreach($a as $key) {
                $total = $total + $this->getProcess($key)->duration;
                $this->durationLookup[$key] = $total;
            }
        }
    }

    public function getDuration($key)
    {
        return $this->durationLookup[$key] ?? null;
    }

    /**
     * @param array $processes
     * @return void
     */
    private function setKeyById(array $processes): void
    {
        $this->keydById = collection($processes)
            ->indexBy(function ($process) {
                return $process->id;
            })
            ->toArray();
    }

    /**
     * @return void
     */
    private function setThreadEnds(): void
    {
        collection($this->keyedByPrereq)
            ->each(function ($node, $id) {
                if (empty($node)) {
                    $this->threadEnds[] = $id;
                }
            });
    }

    public function getGridDimensions()
    {
        $columns = count($this->threadPaths);
        $rows = collection($this->threadPaths)
            ->reduce(function($accum, $path){
                $array = explode('.', $path);
                $accum = count($array) > $accum ? count($array) : $accum;
                return $accum;
            },0);

        return ['threads' => $columns, 'steps' =>$rows];
    }

    public function getMaxDuration()
    {
        return max($this->durationLookup);
    }

}

