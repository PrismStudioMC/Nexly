<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class FlowerPottableBlockComponent extends BlockComponent
{
    public function __construct(
    ) {
    }

    /**
     * Returns the name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::FLOWER_POTTABLE->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create();
    }
}
