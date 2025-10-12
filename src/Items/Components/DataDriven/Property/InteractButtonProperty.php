<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class InteractButtonProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly bool|string $value
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::INTERACT_BUTTON->getValue();
    }

    /**
     * @return StringTag|ByteTag
     */
    public function toNBT(): StringTag|ByteTag
    {
        return is_string($this->value) ? new StringTag($this->value) : new ByteTag($this->value);
    }
}
