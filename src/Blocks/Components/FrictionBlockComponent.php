<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class FrictionBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly float $value = 0.0,
    ) {
        if ($this->value < 0.0 || $this->value > 0.9) {
            throw new \InvalidArgumentException("Friction value must be between 0.0 and 0.9");
        }
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::FRICTION->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("value", new FloatTag($this->value));
    }
}
