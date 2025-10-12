<?php

namespace Nexly\Events\Impl;

use Nexly\Blocks\NexlyBlocks;
use Nexly\Events\Event;
use Nexly\Items\Creative\CreativeInfo;

class BlockRegistryEvent extends Event
{
    private int $count = 0;

    /**
     * @param string $stringId
     * @param \Closure $block
     * @param CreativeInfo|null $creativeInfo
     * @return self
     */
    public function register(string $stringId, \Closure $block, ?CreativeInfo $creativeInfo = null): self
    {
        NexlyBlocks::register($stringId, $block, $creativeInfo);
        $this->count++;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
