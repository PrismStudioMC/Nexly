<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Blocks\Components\Types\MovementType;
use Nexly\Blocks\Components\Types\StickyType;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class MovableBlockComponent extends BlockComponent
{
    /**
     * @param MovementType $movementType
     * @param StickyType|null $sticky
     */
    public function __construct(
        protected MovementType $movementType = MovementType::PUSH_PULL,
        protected ?StickyType $sticky = null
    ) {
        if ($sticky === StickyType::SAME && $movementType !== MovementType::PUSH_PULL) {
            throw new InvalidArgumentException('Sticky type SAME only works with push_pull movement.');
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::MOVABLE->value;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $tag = CompoundTag::create()->setString('movement_type', $this->movementType->value);
        if ($this->sticky !== null) {
            $tag->setString('sticky', $this->sticky->value);
        }
        return $tag;
    }
}
