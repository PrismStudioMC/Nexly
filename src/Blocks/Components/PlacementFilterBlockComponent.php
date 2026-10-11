<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\PlacementCondition;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class PlacementFilterBlockComponent extends BlockComponent
{
    public function __construct(
        protected array $conditions,
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::PLACEMENT_FILTER;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag('conditions', new ListTag(array_map(fn (PlacementCondition $condition): CompoundTag => $condition->toNBT(), $this->conditions)));
    }
}
