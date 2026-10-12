<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class SoundBlockComponent extends BlockComponent
{
    /**
     * @param string $sound
     */
    public function __construct(
        private readonly string $sound,
    ) {
        if ($this->sound === "") {
            throw new InvalidArgumentException("Block sound type cannot be empty.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::SOUND;
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("sound", new StringTag($this->sound));
    }
}
