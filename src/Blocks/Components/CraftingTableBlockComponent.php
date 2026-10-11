<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class CraftingTableBlockComponent extends BlockComponent
{
    /**
     * @param array $craftingTags
     * @param string|null $tableName
     */
    public function __construct(
        protected array $craftingTags,
        protected ?string $tableName = null
    ) {
        if (count($craftingTags) > 64) {
            throw new InvalidArgumentException('At most 64 crafting tags are allowed.');
        }
        foreach ($craftingTags as $tag) {
            if (strlen($tag) > 64) {
                throw new InvalidArgumentException('Crafting tags are limited to 64 characters.');
            }
        }

        if ($tableName === '') {
            throw new InvalidArgumentException('Crafting table name cannot be empty when provided.');
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::CRAFTING_TABLE->getValue();
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create();

        if ($this->tableName !== null) {
            $nbt->setTag('table_name', new StringTag($this->tableName));
        }
        return $nbt
            ->setTag('crafting_tags', new ListTag(array_map(fn (string $value) => new StringTag($value), $this->craftingTags), NBT::TAG_String));
    }
}
