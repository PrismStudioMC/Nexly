<?php

namespace Nexly\Blocks\Traits;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

readonly class State
{
    public function __construct(
        private bool $cardinal = false,
        private bool $facing = false,
        private bool $sixteenWayRotation = false
    ) {
    }

    /**
     * @return bool
     */
    public function isCardinal(): bool
    {
        return $this->cardinal;
    }

    /**
     * @return bool
     */
    public function isFacing(): bool
    {
        return $this->facing;
    }

    /**
     * @return bool
     */
    public function isSixteenWayRotation(): bool
    {
        return $this->sixteenWayRotation;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("cardinal_direction", new ByteTag($this->cardinal))
            ->setTag("facing_direction", new ByteTag($this->facing))
            ->setTag("sixteen_way_rotation", new ByteTag($this->sixteenWayRotation));
    }
}
