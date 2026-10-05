<?php

namespace Nexly\Items\Components\DataDriven\Types;

use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\Axe;
use pocketmine\item\Bow;
use pocketmine\item\FishingRod;
use pocketmine\item\Hoe;
use pocketmine\item\Item;
use pocketmine\item\Pickaxe;
use pocketmine\item\Shears;
use pocketmine\item\Shovel;
use pocketmine\item\Sword;

enum ItemEnchantSlot: string
{
    case SWORD = "sword";
    case HOE = "hoe";
    case SHOVEL = "shovel";
    case PICKAXE = "pickaxe";
    case AXE = "axe";
    case BOW = "bow";
    case CROSSBOW = "crossbow";
    case ELYTRA = "elytra";
    case FISHING_ROD = "fishing_rod";
    case SHEARS = "shears";
    case SHIELD = "shield";
    case FLINTSTEEL = "flintsteel";

    case ARMOR_HEAD = "armor_head";
    case ARMOR_TORSO = "armor_torso";
    case ARMOR_LEGS = "armor_legs";
    case ARMOR_FEET = "armor_feet";

    case ALL = "all";

    /**
     * The name of the enchantment slot.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * The value of the enchantment slot.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get the enchantment slot from armor type info.
     *
     * @param int $slotArmor
     * @return ItemEnchantSlot
     */
    public static function fromArmorTypeInfo(int $slotArmor): ItemEnchantSlot
    {
        return match ($slotArmor) {
            ArmorInventory::SLOT_HEAD => self::ARMOR_HEAD,
            ArmorInventory::SLOT_CHEST => self::ARMOR_TORSO,
            ArmorInventory::SLOT_LEGS => self::ARMOR_LEGS,
            ArmorInventory::SLOT_FEET => self::ARMOR_FEET,
            default => self::ALL
        };
    }

    /**
     * Get the enchantment slot from an item instance.
     *
     * @param Item $item
     * @return ItemEnchantSlot
     */
    public static function fromItem(Item $item): ItemEnchantSlot
    {
        return match (true) {
            $item instanceof Sword => ItemEnchantSlot::SWORD,
            $item instanceof Pickaxe => ItemEnchantSlot::PICKAXE,
            $item instanceof Axe => ItemEnchantSlot::AXE,
            $item instanceof Hoe => ItemEnchantSlot::HOE,
            $item instanceof Shovel => ItemEnchantSlot::SHOVEL,
            $item instanceof Bow => ItemEnchantSlot::BOW,
            //$item instanceof Crossbow => ItemEnchantSlot::CROSSBOW, TODO: Add CrossBow class
            //$item instanceof Elytra => ItemEnchantSlot::ELYTRA, TODO: Add Elytra class
            $item instanceof FishingRod => ItemEnchantSlot::FISHING_ROD,
            $item instanceof Shears => ItemEnchantSlot::SHEARS,
            //$item instanceof Shield => ItemEnchantSlot::SHIELD, TODO: Add Shield class
            $item instanceof Armor => self::fromArmorTypeInfo($item->getArmorSlot()),
            default => ItemEnchantSlot::ALL
        };
    }
}
