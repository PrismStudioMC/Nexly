<?php

namespace Nexly\Blocks\Components\Types;

use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

class ItemMiningSpeed
{
    /**
     * @param string $item
     * @param float $destroySpeed
     */
    public function __construct(
        protected string $item,
        protected float $destroySpeed,
    ) {
        if ($item === "") {
            throw new InvalidArgumentException("Item identifier cannot be empty.");
        }

        if ($destroySpeed < 0.0) {
            throw new InvalidArgumentException("Item-specific destroy speed cannot be negative.");
        }
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("item", new StringTag($this->item))
            ->setTag("destroy_speed", new FloatTag($this->destroySpeed));
    }
}
