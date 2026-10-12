<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class TickBlockComponent extends BlockComponent
{
    /**
     * @param int $min
     * @param int $max
     * @param bool $looping
     */
    public function __construct(
        protected int $min = 20,
        protected int $max = 60,
        protected bool $looping = true
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::TICK;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag('interval_range', new ListTag([
                new IntTag($this->min),
                new IntTag($this->max)
            ], NBT::TAG_Int))
            ->setByte('looping', (int) $this->looping);
    }
}
