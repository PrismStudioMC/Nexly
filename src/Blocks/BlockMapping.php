<?php

namespace Nexly\Blocks;

use pocketmine\block\Block;
use pocketmine\network\mcpe\protocol\types\BlockPaletteEntry;

class BlockMapping
{
    public function __construct(
        private BlockBuilder $builder,
        private BlockPaletteEntry $entry,
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
     * @return string
     */
    public function getStringId(): string
    {
        return $this->builder->getStringId();
    }

    /**
     * @return Block
     */
    public function getBlock(): Block
    {
        $builder = $this->getBuilder();
        return ($builder->getBlock())($builder->getNumericId());
    }

    /**
     * @return BlockPaletteEntry
     */
    public function getEntry(): BlockPaletteEntry
    {
        return $this->entry;
    }
}
