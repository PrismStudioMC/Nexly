<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\ItemEnchantSlot;
use pocketmine\item\Item;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class EnchantableSlotProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly ItemEnchantSlot $slot
    ) {
    }

    /**
     * Create an EnchantableSlotProperty from an Item.
     *
     * @param Item $item
     * @return EnchantableSlotProperty
     */
    public static function fromItem(Item $item): EnchantableSlotProperty
    {
        return new self(ItemEnchantSlot::fromItem($item));
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::ENCHANTABLE_SLOT->getValue();
    }

    /**
     * @return StringTag
     */
    public function toNBT(): StringTag
    {
        return new StringTag($this->slot->getValue());
    }
}
