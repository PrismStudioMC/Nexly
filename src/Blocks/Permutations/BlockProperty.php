<?php

namespace Nexly\Blocks\Permutations;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

class BlockProperty
{
    /**
     * @param string $name
     * @param array $values
     */
    public function __construct(
        private readonly string $name,
        private readonly array $values
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function toNBT()
    {
        $values = [];
        foreach ($this->values as $value) {
            $values[] = match (true) {
                is_string($value) => new StringTag($value),
                is_int($value) => new IntTag($value),
                is_bool($value) => new ByteTag($value),
                default => throw new \InvalidArgumentException("Invalid value type for BlockProperty: " . gettype($value)),
            };
        }

        return CompoundTag::create()
            ->setTag("name", new StringTag($this->name))
            ->setTag("enum", new ListTag($values));
    }
}
