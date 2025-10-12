<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DurabilityItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly int $durability,
        private readonly int $min = 0,
        private readonly int $max = 0,
    ) {
        if ($durability < 1) {
            throw new \InvalidArgumentException("Durability must be at least 1");
        }

        if ($min < 0 || $max < 0) {
            throw new \InvalidArgumentException("Min and Max damage chance must be at least 0");
        }

        if ($min > $max) {
            throw new \InvalidArgumentException("Min damage chance cannot be greater than Max damage chance");
        }

        if ($max > 100) {
            throw new \InvalidArgumentException("Max damage chance cannot be greater than 100");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::DURABILITY->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("max_durability", new IntTag($this->durability))
            ->setTag(
                "damage_chance",
                CompoundTag::create()
                ->setTag("min", new IntTag($this->min))
                ->setTag("max", new IntTag($this->max))
            );
    }
}
