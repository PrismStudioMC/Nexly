<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\LavaFlammability;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class FlammableBlockComponent extends BlockComponent
{
    /**
     * @param int $catchChanceModifier
     * @param int $destroyChanceModifier
     * @param LavaFlammability $lavaFlammable
     */
    public function __construct(
        protected int $catchChanceModifier = 5,
        protected int $destroyChanceModifier = 20,
        protected LavaFlammability $lavaFlammable = LavaFlammability::NEVER,
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
            ->setTag("lava_flammable", new StringTag($this->lavaFlammable->value));
    }
}
