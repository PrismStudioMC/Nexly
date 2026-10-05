<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\ItemEnchantSlot;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class EnchantableItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly ItemEnchantSlot $slot,
        private readonly int $value,
    ) {
        if ($value < 0) {
            throw new \InvalidArgumentException("Value must be non-negative");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::ENCHANTABLE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("slot", new StringTag($this->slot->getName()))
            ->setTag("value", new IntTag($this->value));
    }
}
