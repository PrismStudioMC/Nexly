<?php

namespace Nexly\Blocks\Traits;

use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

class BlockTrait
{
    /**
     * @param TraitId $id
     * @param float $rotationOffset
     * @param TraitState $state
     */
    public function __construct(
        protected TraitId    $id,
        protected TraitState $state,
        protected float      $rotationOffset = 0.0,
    ) {
        if ($id === TraitId::PLACEMENT_POSITION && $rotationOffset !== 0.0) {
            throw new InvalidArgumentException("Placement-position traits do not support rotation offsets.");
        }
    }

    /**
     * @return TraitId
     */
    public function getId(): TraitId
    {
        return $this->id;
    }

    /**
     * @return TraitState
     */
    public function getState(): TraitState
    {
        return $this->state;
    }

    /**
     * @return float
     */
    public function getRotationOffset(): float
    {
        return $this->rotationOffset;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("enabled_states", $this->state->toNBT())
            ->setTag("name", new StringTag($this->id->getValue()));

        if ($this->id === TraitId::PLACEMENT_DIRECTION) {
            $nbt->setTag("y_rotation_offset", new FloatTag($this->rotationOffset));
        }
        return $nbt;
    }
}
