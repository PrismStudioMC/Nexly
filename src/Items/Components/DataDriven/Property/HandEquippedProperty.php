<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use pocketmine\nbt\tag\ByteTag;

#[Attribute(Attribute::TARGET_CLASS)]
class HandEquippedProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly bool $value
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::HAND_EQUIPPED->getValue();
    }

    /**
     * @return ByteTag
     */
    public function toNBT(): ByteTag
    {
        return new ByteTag($this->value);
    }
}
