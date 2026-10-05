<?php

namespace Nexly\Blocks\Components;

use pocketmine\nbt\tag\Tag;

abstract class BlockComponent
{
    /**
     * The name of the component.
     *
     * @return string
     */
    abstract public function getName(): string;

    /**
     * Build the NBT tag for this component.
     *
     * @return Tag
     */
    abstract public function toNBT(): Tag;
}
