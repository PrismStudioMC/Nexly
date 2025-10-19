<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;

/**
 * @see CustomComponentsBlockComponent
 * @deprecated
 */
#[Attribute(Attribute::TARGET_CLASS)]
class OnPlayerPlacingBlockComponent extends BlockComponent
{
    public function __construct(
    ) {
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::ON_PLAYER_PLACING->getValue();
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
