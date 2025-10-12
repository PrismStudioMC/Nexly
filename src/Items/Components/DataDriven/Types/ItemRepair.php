<?php

namespace Nexly\Items\Components\DataDriven\Types;

use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;

class ItemRepair
{
    /**
     * @param float|string $amount
     * @param string[] $items
     */
    public function __construct(
        private float|string   $amount,
        private readonly array $items,
    ) {
        if (is_double($this->amount)) {
            $this->amount = strval($this->amount);
        }
    }

    /**
     * Create an ItemRepair component from an amount and a list of items.
     *
     * @param float|string $amount
     * @param Item ...$items
     * @return ItemRepair
     */
    public static function from(float|string $amount, Item ...$items): ItemRepair
    {
        return new self(
            $amount,
            array_map(function (Item $item): string {
                [$rid] = ($converter = TypeConverter::getInstance())->getItemTranslator()->toNetworkId($item);
                return $converter->getItemTypeDictionary()->fromIntId($rid);
            }, $items)
        );
    }

    /**
     * @return float|string
     */
    public function getAmount(): float|string
    {
        return $this->amount;
    }

    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag(
                "repair_amount",
                CompoundTag::create()
                ->setTag("expression", new StringTag($this->amount))
                ->setTag("version", new IntTag(0))
            )
            ->setTag("items", new ListTag(array_map(fn (string $stringId) => new StringTag($stringId), $this->items), NBT::TAG_String));
    }
}
