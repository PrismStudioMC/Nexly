<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class HoverTextColorProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly string $prefix
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::HOVER_TEXT_COLOR->getValue();
    }

    /**
     * @return StringTag
     */
    public function toNBT(): StringTag
    {
        return new StringTag($this->prefix);
    }
}
