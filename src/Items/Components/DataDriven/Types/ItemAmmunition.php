<?php

namespace Nexly\Items\Components\DataDriven\Types;

use pocketmine\item\Item;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;

class ItemAmmunition
{
    public function __construct(
        private readonly string $item,
        private readonly bool $searchInventory = true,
        private readonly bool $useInCreative = true,
        private readonly bool $useOffHand = true
    ) {
    }

    /**
     * Create an ItemAmmunition component from an Item instance.
     *
     * @param Item $item
     * @param bool $searchInventory
     * @param bool $useInCreative
     * @param bool $useOffHand
     * @return self
     */
    public static function from(
        Item $item,
        bool $searchInventory = true,
        bool $useInCreative = true,
        bool $useOffHand = true
    ): self {
        [$rid] = ($converter = TypeConverter::getInstance())->getItemTranslator()->toNetworkId($item);
        return new self(
            $converter->getItemTypeDictionary()->fromIntId($rid),
            $searchInventory,
            $useInCreative,
            $useOffHand
        );
    }

    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("item", new StringTag($this->item))
            ->setTag("search_inventory", new ByteTag($this->searchInventory))
            ->setTag("use_in_creative", new ByteTag($this->useInCreative))
            ->setTag("use_offhand", new ByteTag($this->useOffHand));
    }
}
