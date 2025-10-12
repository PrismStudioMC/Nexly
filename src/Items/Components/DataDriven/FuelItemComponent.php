<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class FuelItemComponent extends DataDrivenItemComponent
{
    /**
     * @param bool $value
     */
    public function __construct(
        private readonly float $duration
    ) {
        if ($this->duration < 0.05) {
            throw new \InvalidArgumentException("Duration must be at least 0.05");
        }

        if ($this->duration > 107374180) {
            throw new \InvalidArgumentException("Duration must be at most 107374180");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::FUEL->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("duration", new FloatTag($this->duration));
    }
}
