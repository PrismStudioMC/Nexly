<?php

namespace Nexly\Items\Components\Legacy;

use Attribute;
use pocketmine\block\Block;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

#[Attribute(Attribute::TARGET_CLASS)]
class BlockRenderComponent extends LegacyItemComponent
{
    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return LegacyComponentIds::BLOCK_RENDER->getValue();
    }

    /**
     * Create a BlockComponent from a Block instance.
     *
     * @param Block $block
     * @return self
     */
    public static function from(Block $block): self
    {
        return new self(
            GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(),
        );
    }

    public function __construct(
        private readonly string $name,
    ) {
    }

    /**
     * Get the maximum damage value.
     *
     * @return StringTag
     */
    public function toNBT(): StringTag
    {
        return new StringTag($this->name);
    }
}
