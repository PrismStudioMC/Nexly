<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\SupportShape;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

/**
 * @see https://learn.microsoft.com/en-us/minecraft/creator/reference/content/blockreference/examples/blockcomponents/minecraftblock_support?view=minecraft-bedrock-stable
 * @since 1.21.120
 * @internal
 */
#[Attribute(Attribute::TARGET_CLASS)]
class SupportBlockComponent extends BlockComponent
{
    public function __construct(
        protected string $shape = SupportShape::NONE,
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::SUPPORT;
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("shape", new StringTag(strtolower($this->shape)));
    }
}
