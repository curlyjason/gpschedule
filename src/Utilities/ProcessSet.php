<?php

namespace App\Utilities;

use App\Model\Entity\Job;
use App\Model\Entity\Process;
use Cake\Utility\Hash;
use JetBrains\PhpStorm\ArrayShape;
use RecursiveArrayIterator;

class ProcessSet
{
    const DOT_PATH = true;
    const EXPLODED = false;
    const TO = true;
    const FROM = false;

    protected array $keydById;
    protected array $keyedByPrereq;
    protected array $iteratorSeed = [];
    protected array $prereqChain = [];
    protected array $threadEnds = [];
    protected $durationLookup = [];
    protected $threadPaths;
    protected $threadCountAt;
    protected Job $job;

    /**
     * @param Process[] $processes
     */
    public function __construct(array $processes, Job $job)
    {
        $this->initKeyById($processes);
        $this->initFollowerLookup();
        $this->initThreadEnds();
        $this->initThreadPaths();
        $this->registerDurations($this->threadPaths);
        $this->initIteratorSeed($this->getFollowersOf(''));
        $this->initThreadCountAt();
        $this->initJob($job);
    }

    //<editor-fold desc="CLASS INITIALIZER METHODS">
    protected function initThreadPaths()
    {
        $this->threadPaths = collection($this->getThreadEnds())
            ->reduce(function($accum, $endpoint, $index) {
                $path = $this->buildPathTo($endpoint);
                $accum[$index] = $path;
                return $accum;
            }, []);
    }

    private function initFollowerLookup()
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
     * @param array $processes
     * @return void
     */
    private function initKeyById(array $processes): void
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
    private function initThreadEnds(): void
    {
        collection($this->keyedByPrereq)
            ->each(function ($node, $id) {
                if (empty($node)) {
                    $this->threadEnds[] = $id;
                }
            });
    }

    private function initThreadCountAt()
    {
        collection($this->threadPaths)
            ->map(function($path){
                $array = explode('.', $path);
                collection($array)
                    ->map(function($process){
                        $this->threadCountAt[$process] = ($this->threadCountAt($process) ?? 0) + 1;
                    })->toArray();
            })->toArray();
    }

    private function initJob(Job $job)
    {
        $this->job = $job;
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

    private function getInitialProcessesKeys(): array
    {
        return $this->getFollowersOf('');
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
//</editor-fold>


    //<editor-fold desc="ENTITIES AND ENTITY SETS">
    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    public function getProcesses()
    {
        return $this->keydById;
    }

    /**
     * Return a fresh iterator
     *
     * @return RecursiveArrayIterator
     */
    public function getTreeIterator(): RecursiveArrayIterator
    {
        return new RecursiveArrayIterator($this->iteratorSeed);
    }
    //</editor-fold>

    //<editor-fold desc="STURCTURAL INFORMATION">
    public function getThreadCount()
    {
        return count($this->getThreadEnds());
    }

    public function getLongestThreadStepCount()
    {
        return collection($this->threadPaths)
            ->reduce(function($accum, $path){
                $array = explode('.', $path);
                $accum = count($array) > $accum ? count($array) : $accum;
                return $accum;
            },0);
    }

    #[ArrayShape(['threads' => "int", 'steps' => "int"])]
    public function getGridDimensions()
    {
        $columns = count($this->threadPaths);
        $rows = $this->getLongestThreadStepCount();

        return ['threads' => $columns, 'steps' =>$rows];
    }

    public function getMaxDuration()
    {
        return max($this->durationLookup);
    }

    /**
     * @param bool $as self::DOT_PATH|self::EXPLODE
     * @return array
     */
    public function getThreadPaths($as = self::DOT_PATH)
    {
        if ($as === self::DOT_PATH) {
            return $this->threadPaths;
        }
        return collection($this->threadPaths)
            ->map(function($path) {
                return explode('.', $path);
            })->toArray();
    }

    public function getThreadEnds()
    {
        return $this->threadEnds;
    }

    /**
     * @param bool $direction self::TO|self::FROM
     * @param $process_id
     * @return array
     */
    public function pathSegment(bool $direction, $process_id) : array
    {
        $pattern = $direction
            ? '/.*\W' . $process_id . '/'
            : '/\W' . $process_id . '.*/';

        return collection($this->getThreadPaths())
            ->map(function($path) use ($pattern) {
                return preg_split($pattern, $path);
//                $result = preg_match($pattern, $path, $match);
//                if ($result) {
//                    debug($match);
//                }
            })->toArray();
    }

    protected function makeSetMember($process_id) {
        $construct_args = [
            'followers' => $this->getFollowersOf($process_id),
            'cumulative_duration' => $this->getDuration($process_id),
            'child_thread_count' => $this->threadCountAt($process_id),
            'path' => $this->pathSegment(self::TO, $process_id),
        ];
        return new ProcessSetMember(
            $this->getProcess($process_id),
            $construct_args
        );
    }
    //</editor-fold>

    //<editor-fold desc="LOOKUPS AND DATA BY PROCESS-ID">
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

    public function getDuration($key)
    {
        return $this->durationLookup[$key] ?? null;
    }

    public function threadCountAt($process_id)
    {
        return $this->threadCountAt[$process_id] ?? null;
    }
    //</editor-fold>
}

