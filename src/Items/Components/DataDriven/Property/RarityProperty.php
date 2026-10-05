<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\ItemRarity;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class RarityProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly ItemRarity $rarity
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::RARITY->getValue();
    }

    /**
     * @return StringTag
     */
    public function toNBT(): StringTag
    {
        return new StringTag($this->rarity->getValue());
    }
}
