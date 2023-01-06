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
     * @return string
     */
    public function getPrereqOf($key): ?string
    {
        return $this->keydById[$key]->prereq;
    }

    /**
     * @param $key
     * @return array
     */
    public function getFollowersOf($key): array
    {
        return $this->keyedByPrereq[$key] ?? [];
    }

    public function getProcess($key)
    {
        return $this->keydById[$key] ?? null;
    }

    public function getChildIterator(): \RecursiveArrayIterator
    {
        $this->iteratorSeed = empty($this->iteratorSeed)
            ? $this->initIteratorSeed($this->getFollowersOf(''))
            : $this->iteratorSeed;
        return new \RecursiveArrayIterator($this->initIteratorSeed($this->iteratorSeed));
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
        $followers = $followers ?? $this->getFollowersOf('');
        $split = count($followers) > 1;

        collection ($followers)->map(function($follower, $index) use ($path, $split){

            $path = $split ? $this->split($path, $index) : $path ;
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
        $pathArray = explode('.', $path);
        $last = array_pop($pathArray);
        $pathArray[] = ++$last;
        return implode('.', $pathArray);
    }

    private function split(mixed $path, $index): string
    {
        $pathArray = explode('.', $path);
        $last = array_pop($pathArray);
        $pathArray[] = ($last + $index) . '.0';
        return implode('.', $pathArray);
    }

}
