<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

/**
 * @see CustomComponentsBlockComponent
 * @deprecated
 */
#[Attribute(Attribute::TARGET_CLASS)]
class OnInteractBlockComponent extends BlockComponent
{
    public function __construct(
        private string $condition = "",
        private string $event = "",
        private string $target = "self",
    ) {
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::ON_INTERACT->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("condition",  new StringTag($this->condition))
            ->setTag("event", new StringTag($this->event))
            ->setTag("target", new StringTag($this->target));
    }
}
