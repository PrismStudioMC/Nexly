<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\ItemSlot;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class WearableItemComponent extends DataDrivenItemComponent
{
    /**
     * @param ItemSlot $slot
     * @param int $protection
     */
    public function __construct(
        private readonly ItemSlot $slot,
        private readonly int $protection
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::WEARABLE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("slot", new StringTag($this->slot->getValue()))
            ->setTag("protection", new IntTag($this->protection));
    }
}
