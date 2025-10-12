<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class BundleInteractionItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly int $capacity,
    ) {
        if ($this->capacity < 1 || $this->capacity > 64) {
            throw new \InvalidArgumentException("Capacity must be between 1 and 64");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::BUNDLE_INTERACTION->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("num_viewable_slots", new IntTag($this->capacity));
    }
}
