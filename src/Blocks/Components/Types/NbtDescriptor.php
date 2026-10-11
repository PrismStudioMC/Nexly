<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

class NbtDescriptor
{
    /**
     * @param string|null $name
     * @param array $states
     * @param string|null $tags
     */
    public function __construct(
        protected ?string $name = null,
        protected array $states = [],
        protected ?string $tags = null,
    ) {
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create();
        if ($this->name !== null) {
            $nbt->setString('name', $this->name);
        }
        if ($this->tags !== null) {
            $nbt->setString('tags', $this->tags);
        }
        if (empty($this->states)) {
            $states = CompoundTag::create();
            foreach ($this->states as $name => $value) {
                $states->setTag($name, match (true) {
                    is_bool($value) => new ByteTag($value),
                    is_int($value) => new IntTag($value),
                    default => new StringTag($value),
                });
            }
            $nbt->setTag('states', $states);
        }
        return $nbt;
    }
}
