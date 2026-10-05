<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use pocketmine\item\Item;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class EnchantableValueProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly int $value
    ) {
    }

    /**
     * Create an EnchantableValueProperty from an Item.
     *
     * @param Item $item
     * @return EnchantableValueProperty
     */
    public static function fromItem(Item $item): EnchantableValueProperty
    {
        return new self($item->getEnchantability());
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::ENCHANTABLE_VALUE->getValue();
    }

    /**
     * @return IntTag
     */
    public function toNBT(): IntTag
    {
        return new IntTag($this->value);
    }
}
