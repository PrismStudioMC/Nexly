<?php

namespace Nexly\Items\Components\DataDriven\Types;

use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\VanillaArmorMaterials;

enum OreTextureType: string
{
    case NONE = "none";
    case CHAIN = "chain";
    case DIAMOND = "diamond";
    case ELYTRA = "elytra";
    case GOLD = "gold";
    case IRON = "iron";
    case LEATHER = "leather";
    case NETHERITE = "netherite";
    case TURTLE = "turtle";

    /**
     * Get the string value of the OreTextureType.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get the OreTextureType from an Item instance.
     *
     * @param Item $item
     * @return OreTextureType
     */
    public static function fromItem(Item $item): OreTextureType
    {
        if ($item instanceof Armor) {
            return match (true) {
                $item->getMaterial() === VanillaArmorMaterials::LEATHER() => self::LEATHER,
                $item->getMaterial() === VanillaArmorMaterials::CHAINMAIL() => self::CHAIN,
                $item->getMaterial() === VanillaArmorMaterials::IRON() => self::IRON,
                $item->getMaterial() === VanillaArmorMaterials::GOLD() => self::GOLD,
                $item->getMaterial() === VanillaArmorMaterials::DIAMOND() => self::DIAMOND,
                $item->getMaterial() === VanillaArmorMaterials::NETHERITE() => self::NETHERITE,
                $item->getMaterial() === VanillaArmorMaterials::TURTLE() => self::TURTLE,
                default => self::NONE,
            };
        }
        return self::NONE;
    }
}
