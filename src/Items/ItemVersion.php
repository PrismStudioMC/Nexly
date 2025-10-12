<?php

namespace Nexly\Items;

use Nexly\Items\Components\DataDriven\DataDriven;
use Nexly\Items\Components\DataDriven\ItemDataDriven;
use pocketmine\item\Armor;
use pocketmine\item\Dye;
use pocketmine\item\Item;
use pocketmine\item\ProjectileItem;
use pocketmine\item\Record;
use pocketmine\item\SpawnEgg;
use ReflectionClass;
use ReflectionException;

enum ItemVersion: int
{
    case LEGACY = 0;
    case DATA_DRIVEN = 1;
    case NONE = 2;

    /**
     * Get the integer value of the ItemVersion.
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Check if two ItemVersion instances are equal.
     *
     * @param self $other
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this === $other;
    }

    /**
     * Get the ItemVersion from an Item instance.
     *
     * @param Item $item
     * @return self
     */
    public static function fromItem(Item $item): self
    {
        try {
            $reflection = new ReflectionClass($item::class);
        } catch (ReflectionException $e) {
            return self::NONE; // In case of reflection failure, return NONE
        }

        $attributes = $reflection->getAttributes(DataDriven::class);
        if (
            count($attributes) > 0 ||
            $item instanceof Armor ||
            $item instanceof SpawnEgg ||
            $item instanceof ProjectileItem ||
            $item instanceof Record ||
            $item instanceof Dye
        ) {
            return self::DATA_DRIVEN;
        }

        return self::LEGACY;
    }
}
