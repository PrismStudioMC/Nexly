<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class LightEmissionBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly int $value,
    ) {
        if ($value < 0 || $value > 15) {
            throw new \InvalidArgumentException("Light dampening value must be between 0 and 15");
        }
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::LIGHT_EMISSION->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("emission", new ByteTag($this->value));
    }
}
