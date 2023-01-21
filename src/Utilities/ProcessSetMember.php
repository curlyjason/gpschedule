<?php

namespace App\Utilities;

use App\Model\Entity\Process;

class ProcessSetMember
{

    private Process $process;
    private $followers;

    /**
     * @return mixed
     */
    public function getfollowers(): mixed
    {
        return $this->followers;
    }

    /**
     * @return mixed
     */
    public function getchild_thread_count(): mixed
    {
        return $this->child_thread_count;
    }
    private $cumulative_duration;
    private $child_thread_count;
    private string $path;

    /**
     * @param Process $process
     * @param array $args
     */
    public function __construct(Process $process, array $args)
//    public function __construct(Process $process, string $path, int $prev_duration)
    {
        $this->process = $process;
        $this->followers = $args['followers'];
        $this->cumulative_duration = $args['cummulative_duration'];
        $this->child_thread_count = $args['child_thread_count'];
        $this->setPath($path ?? '');
    }

    public function _invoke() : Process
    {
        return $this->process;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    public function getPath($path)
    {
        return $this->path;
    }

    public function setPrevDuration(int $prev_duration)
    {
        $this->prev_duration = $prev_duration;
    }

    public function getPrevDuration():int
    {
        return $this->cumulative_duration - $this->process->duration;
    }

    public function getDuration():int
    {
        return $this->process->getDuration();
    }

    public function getTotalDuration(): int
    {
        return $this->cumulative_duration;
    }

}
