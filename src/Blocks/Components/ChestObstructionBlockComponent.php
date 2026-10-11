<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\ObstructionRule;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class ChestObstructionBlockComponent extends BlockComponent
{
    /**
     * @param string $rule
     */
    public function __construct(
        private readonly string $rule = ObstructionRule::SHAPE,
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::CHEST_OBSTRUCTION;
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("obstruction_rule", new StringTag($this->rule));
    }
}