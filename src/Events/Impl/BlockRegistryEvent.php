<?php

namespace Nexly\Events\Impl;

use Nexly\Blocks\BlockBuilder;
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
     * @return BlockBuilder
     */
    public function register(string $stringId, \Closure $block, ?CreativeInfo $creativeInfo = null): BlockBuilder
    {
        $this->count++;
        return NexlyBlocks::register($stringId, $block, $creativeInfo);
    }

    /**
     * @param string $stringId
     * @param \Closure $block
     * @param CreativeInfo|null $creativeInfo
     * @return BlockBuilder
     */
    public function build(string $stringId, \Closure $block, ?CreativeInfo $creativeInfo = null): BlockBuilder
    {
        $this->count++;
        return NexlyBlocks::buildComplex($stringId, $block, $creativeInfo);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
