<?php

namespace App\Utilities;

use App\Model\Entity\Process;

class ProcessSetMember
{

    private Process $process;
    private string $path;
    private int $prev_duration;

    /**
     * @param Process $process
     * @param string $path
     * @param int $prev_duration
     */
    public function __construct(Process $process, string $path, int $prev_duration)
    {
        $this->process = $process;
        $this->setPath($path);
        $this->prev_duration = $prev_duration;
    }

    public function getProcess()
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
        return $this->prev_duration;
    }

    public function getDuration():int
    {
        return $this->process->getDuration();
    }

    public function getTotalDuration(): int
    {
        return $this->getDuration() + $this->getPrevDuration();
    }

}
