<?php

namespace Nexly\Blocks\Traits;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

class MinecraftTrait
{
    /**
     * @param TraitIds $identifier
     * @param float $rotationOffset
     * @param State $state
     */
    public function __construct(
        private TraitIds $identifier,
        private float  $rotationOffset,
        private State $state,
    )
    {
    }

    /**
     * @return TraitIds
     */
    public function getIdentifier(): TraitIds
    {
        return $this->identifier;
    }
    /**
     * @return float
     */
    public function getRotationOffset(): float
    {
        return $this->rotationOffset;
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("enabled_states", $this->state->toNBT())
            ->setTag("name", new StringTag($this->identifier->getValue()))
            ->setTag("y_rotation_offset", new FloatTag($this->rotationOffset));
    }
}