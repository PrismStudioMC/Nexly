<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;

#[Attribute(Attribute::TARGET_CLASS)]
class StorageItemComponent extends DataDrivenItemComponent
{
    /**
     * @param int $capacity
     * @param bool $allowNested
     * @param array $allowedItems
     * @param array $bannedItems
     */
    public function __construct(
        private readonly int $capacity,
        private readonly bool $allowNested = true,
        private readonly array $allowedItems = [],
        private readonly array $bannedItems = [],
    ) {
        if ($this->capacity < 0) {
            throw new \InvalidArgumentException("Capacity must be a non-negative integer.");
        }

        if ($this->capacity > 64) {
            throw new \InvalidArgumentException("Capacity must not exceed 64.");
        }
    }

    /**
     * Create a StorageItemComponent.
     *
     * @param int $capacity
     * @param bool $allowNested
     * @param Item[] $allowedItems
     * @param Item[] $bannedItems
     * @return self
     */
    public static function from(
        int $capacity,
        bool $allowNested = true,
        array $allowedItems = [],
        array $bannedItems = []
    ): self {

        $processItem = function (Item $item): string {
            [$rid] = ($converter = TypeConverter::getInstance())->getItemTranslator()->toNetworkId($item);
            return $converter->getItemTypeDictionary()->fromIntId($rid);
        };

        return new self(
            $capacity,
            $allowNested,
            array_map($processItem, $allowedItems),
            array_map($processItem, $bannedItems)
        );
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::STORAGE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("max_slots", new IntTag($this->capacity))
            ->setTag("allow_nested_storage_items", new ByteTag($this->allowNested))
            ->setTag("allowed_items", new ListTag(array_map(fn (string $item) => new StringTag($item), $this->allowedItems), NBT::TAG_String))
            ->setTag("banned_items", new ListTag(array_map(fn (string $item) => new StringTag($item), $this->bannedItems), NBT::TAG_String));
    }
}
