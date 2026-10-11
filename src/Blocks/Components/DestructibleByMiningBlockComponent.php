<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DestructibleByMiningBlockComponent extends BlockComponent
{
    public function __construct(
        protected float $secondsToDestroy = 0.0,
        protected array $itemSpecificSpeeds = [],
    ) {
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::DESTRUCTIBLE_BY_MINING;
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $items = array_map(fn (ItemSpecificSpeed $speed) => $speed->toNBT(), $this->itemSpecificSpeeds);
        return CompoundTag::create()
            ->setTag("seconds_to_destroy", new FloatTag($this->secondsToDestroy))
            ->setTag("item_specific_speeds", new ListTag($items, NBT::TAG_Compound));
    }
}
