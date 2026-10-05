<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class MiningSpeedProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly float $speed
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::MINING_SPEED->getValue();
    }

    /**
     * @return FloatTag
     */
    public function toNBT(): FloatTag
    {
        return new FloatTag($this->speed);
    }
}
