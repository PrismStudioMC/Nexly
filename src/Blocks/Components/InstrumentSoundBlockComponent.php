<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class InstrumentSoundBlockComponent extends BlockComponent
{
    public function __construct(
        private ?string $up = null,
        private ?string $down = null,
    ) {
        if ($up === null && $down === null) {
            throw new InvalidArgumentException("At least one instrument sound face ('up' or 'down') must be defined.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::INSTRUMENT_SOUND;
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create();
        if ($this->up !== null) {
            $nbt->setTag("up", new StringTag($this->up));
        }
        if ($this->down !== null) {
            $nbt->setTag("down", new StringTag($this->down));
        }

        return $nbt;
    }
}
