<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class LootBlockComponent extends BlockComponent
{
    /**
     * @param string $path
     */
    public function __construct(
        protected string $path
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::LOOT->value;
    }

    /**
     * @return StringTag
     */
    public function toNBT(): StringTag
    {
        return new StringTag($this->path);
    }
}
