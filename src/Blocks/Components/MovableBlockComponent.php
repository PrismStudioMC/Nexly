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
     * @param string $movementType
     * @param string|null $sticky
     */
    public function __construct(
        protected string $movementType = MovementType::PUSH_PULL,
        protected ?string $sticky = null
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
        return BlockComponentIds::MOVABLE;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $tag = CompoundTag::create()->setString('movement_type', $this->movementType);
        if ($this->sticky !== null) {
            $tag->setString('sticky', $this->sticky);
        }
        return $tag;
    }
}
