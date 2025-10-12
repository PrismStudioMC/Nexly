<?php

namespace Nexly\Items\Components\DataDriven\Types;

use pocketmine\inventory\ArmorInventory;

enum ItemSlot: string
{
    case ARMOR = "slot.armor";
    case ARMOR_CHEST = "slot.armor.chest";
    case ARMOR_FEET = "slot.armor.feet";
    case ARMOR_HEAD = "slot.armor.head";
    case ARMOR_LEGS = "slot.armor.legs";
    case CHEST = "slot.chest";
    case ENDERCHEST = "slot.enderchest";
    case EQUIPPABLE = "slot.equippable";
    case HOTBAR = "slot.hotbar";
    case INVENTORY = "slot.inventory";
    case NONE = "none";
    case SADDLE = "slot.saddle";
    case WEAPON_MAIN_HAND = "slot.weapon.mainhand";
    case WEAPON_OFF_HAND = "slot.weapon.offhand";

    /**
     * Get the string name of the ItemSlot.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the string value of the ItemSlot.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get the ItemSlot from an Item instance.
     *
     * @param int $slotArmor
     * @return ItemSlot
     */
    public static function fromArmorTypeInfo(int $slotArmor): ItemSlot
    {
        return match ($slotArmor) {
            ArmorInventory::SLOT_CHEST => self::ARMOR_CHEST,
            ArmorInventory::SLOT_HEAD => self::ARMOR_HEAD,
            ArmorInventory::SLOT_LEGS => self::ARMOR_LEGS,
            ArmorInventory::SLOT_FEET => self::ARMOR_FEET,
            default => self::ARMOR
        };
    }
}
