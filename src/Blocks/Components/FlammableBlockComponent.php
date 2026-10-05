<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class FlammableBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly int $catchChanceModifier = 5,
        private readonly int $destroyChanceModifier = 20,
        private readonly bool $lavaFlammable = false,
    ) {
        if ($catchChanceModifier < 0 || $destroyChanceModifier < 0) {
            throw new \InvalidArgumentException("Flammability modifiers cannot be negative.");
        }
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::FLAMMABLE->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("catch_chance_modifier", new IntTag($this->catchChanceModifier))
            ->setTag("destroy_chance_modifier", new IntTag($this->destroyChanceModifier))
            ->setTag("lava_flammable", new ByteTag($this->lavaFlammable ? 1 : 0));
    }
}
