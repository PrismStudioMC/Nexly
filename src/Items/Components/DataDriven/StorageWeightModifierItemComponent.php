<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class StorageWeightModifierItemComponent extends DataDrivenItemComponent
{
    /**
     * @param int $capacity
     */
    public function __construct(
        private readonly int $capacity,
    ) {
        if ($capacity < 0) {
            throw new \InvalidArgumentException("Capacity must be a non-negative integer.");
        }

        if ($capacity > 64) {
            throw new \InvalidArgumentException("Capacity cannot exceed 64.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::STORAGE_WEIGHT_MODIFIER->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("weight_in_storage_item", new IntTag($this->capacity));
    }
}
