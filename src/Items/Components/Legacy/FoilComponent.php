<?php

namespace Nexly\Items\Components\Legacy;

use Attribute;
use pocketmine\nbt\tag\ByteTag;

#[Attribute(Attribute::TARGET_CLASS)]
class FoilComponent extends LegacyItemComponent
{
    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return LegacyComponentIds::FOIL->getValue();
    }

    public function __construct(
        private readonly bool $value = true
    ) {
    }

    /**
     * Get the maximum damage value.
     *
     * @return ByteTag
     */
    public function toNBT(): ByteTag
    {
        return new ByteTag($this->value);
    }
}
