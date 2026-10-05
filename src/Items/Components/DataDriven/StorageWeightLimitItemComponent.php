<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class StorageWeightLimitItemComponent extends DataDrivenItemComponent
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
        return DataDrivenComponentIds::STORAGE_WEIGHT_LIMIT->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("max_weight_limit", new IntTag($this->capacity));
    }
}
