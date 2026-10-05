<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class IconProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly string $icon,
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::ICON->getValue();
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("texture", new StringTag($this->icon))
            ->setTag(
                "textures",
                CompoundTag::create()
                ->setTag("default", new StringTag($this->icon))
                ->setTag("dyed", new StringTag($this->icon . ".dyed"))
                ->setTag("icon_trim", new StringTag($this->icon . ".trim"))
            );
    }
}
