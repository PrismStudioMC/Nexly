<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class EntityFallOnBlockComponent extends BlockComponent
{
    /**
     * @param float $minFallDistance
     */
    public function __construct(
        protected float $minFallDistance = 0.0
    ) {
        if ($minFallDistance < 0.0) {
            throw new InvalidArgumentException('Minimum fall distance cannot be negative.');
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::ENTITY_FALL_ON;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag('min_fall_distance', new FloatTag($this->minFallDistance));
    }
}
