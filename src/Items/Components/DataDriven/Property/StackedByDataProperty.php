<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use pocketmine\nbt\tag\ByteTag;

#[Attribute(Attribute::TARGET_CLASS)]
class StackedByDataProperty extends PropertyItemComponent
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
        return PropertyComponentIds::STACKED_BY_DATA->getValue();
    }

    /**
     * @return ByteTag
     */
    public function toNBT(): ByteTag
    {
        return new ByteTag($this->value);
    }
}
