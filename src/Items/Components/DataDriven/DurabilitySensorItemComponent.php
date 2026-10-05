<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\DurabilityThreshold;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DurabilitySensorItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly array $thresholds,
    ) {
        if (empty($this->thresholds)) {
            throw new \InvalidArgumentException("Thresholds array cannot be empty.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::DURABILITY_SENSOR->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("durability_thresholds", new ListTag(array_map(fn (DurabilityThreshold $threshold) => $threshold->toNBT(), $this->thresholds), NBT::TAG_Compound));
    }
}
