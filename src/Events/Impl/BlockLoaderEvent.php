<?php

namespace Nexly\Events\Impl;

use Nexly\Blocks\BlockBuilder;
use Nexly\Events\Event;
use pocketmine\block\Block;

class BlockLoaderEvent extends Event
{
    private bool $affected = false;

    public function __construct(
        private BlockBuilder $builder,
        private Block $block
    ) {
    }

    /**
     * @return BlockBuilder
     */
    public function getBuilder(): BlockBuilder
    {
        return $this->builder;
    }

    /**
     * @return Block
     */
    public function getBlock(): Block
    {
        return $this->block;
    }

    /**
     * @return bool
     */
    public function isAffected(): bool
    {
        return $this->affected;
    }

    /**
     * @param bool $affected
     */
    public function affected(bool $affected = true): void
    {
        $this->affected = $affected;
    }
}
